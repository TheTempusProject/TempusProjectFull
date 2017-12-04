<?php
/**
 * Controllers/register.php
 *
 * This is the register controller.
 *
 * @version 1.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */

namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Classes\Session as Session;
use TempusProjectCore\Classes\Cookie as Cookie;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Email as Email;
use TempusProjectCore\Classes\Issue as Issue;
use TempusProjectCore\Classes\Check as Check;
use TempusProjectCore\Classes\Code as Code;
use TempusProjectCore\Classes\Hash as Hash;
use TempusProjectCore\Classes\Token as Token;
use TempusProjectCore\Classes\Redirect as Redirect;

class Register extends Controller
{
    public function __construct()
    {
        self::$template->noIndex();
    }

    public function __destruct()
    {
        Debug::log('Controller Destructing: ' . get_class($this));
        self::$session->updatePage(self::$title);
        $this->build();
        Debug::closeAllGroups();
    }

    public function index()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Register';
        self::$template->set('TERMS', self::$template->standardView('terms'));
        if (self::$isLoggedIn) {
            Issue::notice('You are currently logged in.');
            exit();
        }
        if (!Input::exists()) {
            $this->view('register');
            exit();
        }
        if (!Check::form('register')) {
            Issue::error('There was an error with your registration.', Check::userErrors());
            $this->view('register');
            exit();
        }
        $code = Code::genConfirmation();
        self::$user->create([
            'username' => Input::post('username'),
            'password' => Hash::make(Input::post('password')),
            'email' => Input::post('email'),
            'registered' => time(),
            'confirmationCode' => $code,
            'terms' => 1,
        ]);
        Email::send(Input::post('email'), 'confirmation', $code, ['template' => true]);
        Session::flash('success', 'Thank you for registering! Please check your email to confirm your account.');
        Redirect::to('home');
    }
    /**
     * @todo  Come back and separate this into multiple forms because this is gross.
     */
    public function recover()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Recover Account - {SITENAME}';
        self::$template->noIndex();
        if (!Input::exists()) {
            $this->view('forgot');
            exit();
        }
        if (Check::email(Input::post('entry')) && self::$user->findByEmail(Input::post('entry'))) {
            $userData = self::$user->data();
            Email::send($userData->email, 'forgotUsername', $userData->username, ['template' => true]);
            Issue::notice('Your Username has been sent to your registered email address.');
            Redirect::to('home/index');
        } elseif (self::$user->get(Input::post('entry'))) {
            self::$user->newCode(self::$user->data()->ID);
            self::$user->get(Input::post('entry'));
            $userData = self::$user->data();
            Email::send($userData->email, 'forgot' . $type, $userData->confirmationCode, ['template' => true]);
            Issue::notice('Details for resetting your password have been sent to your registered email address');
            Redirect::to('home/index');
        }
        Issue::error('User not found.');
        $this->view('forgot');
        exit();
    }

    public function confirm($code = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Confirm Email';
        if (!isset($code) && !Input::exists('confirmationCode')) {
            $this->view('email.confirmation');
            exit();
        }
        if (Check::form('emailConfirmation')) {
            $code = Input::post('confirmationCode');
        }
        if (!self::$user->confirm($code)) {
            Issue::error('There was an error confirming your account, please try again.');
            $this->view('email.confirmation');
            exit();
        }
        Session::flash('success', 'You have successfully confirmed your email address.');
        Redirect::to('home/index');
    }

    public function resend()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Resend Confirmation';
        if (!self::$isLoggedIn) {
            Issue::notice('Please log in to resend your confirmation email.');
            exit();
        }
        if (self::$activeUser->data()->confirmed == '1') {
            Issue::notice('Your account has already been confirmed.');
            exit();
        }
        if (!Check::form('confirmationResend')) {
            $this->view('email.confirmation.resend');
            exit();
        }
        Email::send(self::$activeUser->data()->email, 'confirmation', self::$activeUser->data()->confirmationCode, ['template' => true]);
        Session::flash('success', 'Your confirmation email has been sent to the email for your account.');
        Redirect::to('home/index');
    }

    public function reset($code = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Password Reset';
        if (!isset($code) && !Input::exists('resetCode')) {
            Issue::error('No reset code provided.');
            $this->view('password.reset.code');
            exit();
        }
        if (Input::exists('resetCode')) {
            if (Check::form('passwordResetCode')) {
                $code = Input::post('resetCode');
            }
        }
        if (self::$user->checkCode($code)) {
            Issue::error('There was an error with your reset code. Please try again.');
            $this->view('password.reset.code');
            exit();
        }
        self::$template->set('resetCode', $code);
        if (!Input::exists()) {
            $this->view('password.reset');
            exit();
        }
        if (!Check::form('passwordReset')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('password.reset');
            exit();
        }
        self::$user->changePassword($code, Input::post('password'));
        Email::send(self::$user->data()->email, 'passwordChange', null, ['template' => true]);
        Session::flash('success', 'Your Password has been changed, please use your new password to log in.');
        Regirect::to('home/login');
    }
}
