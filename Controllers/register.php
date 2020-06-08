<?php
/**
 * Controllers/register.php
 *
 * This is the register controller.
 *
 * @version 3.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Session;
use TempusProjectCore\Classes\Cookie;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Email;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Code;
use TempusProjectCore\Classes\Hash;
use TempusProjectCore\Classes\Redirect;

class Register extends Controller
{
    protected static $recaptcha;
    protected static $session;
    protected static $user;
    
    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$template->noIndex();
        self::$session = $this->model('sessions');
        self::$recaptcha = $this->model('recaptcha');
        self::$user = $this->model('user');
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
        if (!self::$recaptcha->verify(Input::post('g-recaptcha-response'))) {
            Issue::error('There was an error with your login.', self::$recaptcha->getErrors());
            $this->view('login');
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
        Redirect::to('home/index');
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
            Email::send($userData->email, 'forgotPassword', $userData->confirmationCode, ['template' => true]);
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
            $this->view('email.confirmationResend');
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
            $this->view('passwordResetCode');
            exit();
        }
        if (Input::exists('resetCode')) {
            if (Check::form('passwordResetCode')) {
                $code = Input::post('resetCode');
            }
        }
        if (!self::$user->checkCode($code)) {
            Issue::error('There was an error with your reset code. Please try again.');
            $this->view('passwordResetCode');
            exit();
        }
        self::$template->set('resetCode', $code);
        if (!Input::exists()) {
            $this->view('passwordReset');
            exit();
        }
        if (!Check::form('passwordReset')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('passwordReset');
            exit();
        }
        self::$user->changePassword($code, Input::post('password'));
        Email::send(self::$user->data()->email, 'passwordChange', null, ['template' => true]);
        Session::flash('success', 'Your Password has been changed, please use your new password to log in.');
        Redirect::to('home/login');
    }
}
