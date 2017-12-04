<?php
/**
 * Controllers/home.php
 *
 * This is the home controller.
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
use TempusProjectCore\Classes\Redirect as Redirect;
use TempusProjectCore\Classes\Session as Session;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\Cookie as Cookie;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Email as Email;
use TempusProjectCore\Classes\Check as Check;
use TempusProjectCore\Classes\Issue as Issue;
use TempusProjectCore\Classes\Log as Log;
use TempusProjectCore\Classes\DB as DB;

class Home extends Controller
{
    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
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
        self::$title = '{SITENAME}';
        self::$pageDescription = 'This is the homepage of your new Tempus Project Installation. Thank you for installing. find more info at http://www.thetempusproject.com';
        $this->view('index');
        exit();
    }

    public function subscribe()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Subscribe - {SITENAME}';
        self::$pageDescription = 'We are always publishing great content and keeping our members up to date. If you would like to join our list, you can subscribe here.';
        if (!Input::exists('email')) {
            $this->view('subscribe');
            exit();
        }
        if (!Check::form('subscribe')) {
            Issue::notice('There was an error with your request.');
            $this->view('subscribe');
            exit();
        }
        if (!self::$subscribe->add(Input::post('email'))) {
            Issue::error('There was an error with your request, please try again.');
            $this->view('subscribe');
            exit();
        }
        $data = self::$subscribe->get(Input::post('email'));
        Email::send(Input::post('email'), 'subscribe', $data->confirmationCode, ['template' => true]);
        Issue::success('You have successfully been subscribed to our mailing list.');
        exit();
    }

    public function unsubscribe($email = null, $code = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = '{SITENAME}';
        self::$pageDescription = '';
        if (!empty($email) && !empty($code)) {
            if (self::$subscribe->unsubscribe($email, $code)) {
                Issue::success('You have been successfully unsubscribed from receiving further mailings.');
                exit();
            }
            Issue::error('There was an error with your request.');
            $this->view('unsubscribe');
            exit();
        }
        if (!Input::exists('submit')) {
            $this->view('unsubscribe');
            exit();
        }
        if (!Check::form('unsubscribe')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('unsubscribe');
            exit();
        }
        $data = self::$subscribe->get(Input::post('email'));
        if (empty($data)) {
            Issue::notice('There was an error with your request.');
            $this->view('unsubscribe');
            exit();
        }
        Email::send(Input::post('email'), 'unsubInstructions', $data->confirmationCode, ['template' => true]);
        Session::flash('success', 'An email with instructions on how to unsubscribe has been sent to your email.');
        Redirect::to('home/index');
    }

    public function feedback()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Feedback - {SITENAME}';
        self::$pageDescription = 'At {SITENAME}, we value our users\' input. You can provide any feedback or suggestions using this form.';
        if (!Input::exists()) {
            $this->view('feedback');
            exit();
        }
        if (!Check::form('feedback')) {
            Issue::error('There was an error with your form, please check your submission and try again.', Check::userErrors());
            $this->view('feedback');
            exit();
        }
        self::$feedback->create(Input::post('name'), Input::post('email'), Input::post('entry'));
        Session::flash('success', 'Thank you! Your feedback has been received.');
        Redirect::to('home/index');
    }

    public function bugreport()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Bug Report - {SITENAME}';
        self::$pageDescription = 'On this page you can submit a bug report for the site.';
        if (!self::$isLoggedIn) {
            Issue::notice('You must be logged in to report bugs.');
            exit();
        }
        if (!Input::exists()) {
            $this->view('bug.report');
            exit();
        }
        if (!Check::form('bugreport')) {
            Issue::error('There was an error with your report.', Check::userErrors());
            $this->view('bug.report');
            exit();
        }
        self::$bugreport->create(self::$activeUser->ID, Input::post('url'), Input::post('ourl'), Input::post('repeat'), Input::post('entry'));
        Session::flash('success', 'Your Bug Report has been received. We may contact you for more information at the email address you provided.');
        Redirect::to('home/index');
    }

    public function profile($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'User Profile - {SITENAME}';
        self::$pageDescription = 'User Profiles for {SITENAME}';
        if (!self::$isLoggedIn) {
            Issue::notice('You must be logged in to view this page.');
            exit();
        }
        $user = self::$user->get($data);
        if (!$user) {
            Issue::notice("No user found.");
            exit();
        }
        self::$title = $user->username . '\'s Profile - {SITENAME}';
        self::$pageDescription = 'User Profile for '.$user->username.' - {SITENAME}';
        $this->view('user', $user);
    }

    public function login()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Portal - {SITENAME}';
        self::$pageDescription = 'Please log in to use {SITENAME} member features.';
        if (self::$isLoggedIn) {
            Issue::notice('You are already logged in. Please <a href="'.Docroot::getAddress().'home/logout">click here</a> to log out.');
            exit();
        }
        if (!Input::exists()) {
            $this->view('login');
            exit();
        }
        if (!Check::form('login')) {
            Issue::error('There was an error with your login.', Check::userErrors());
            $this->view('login');
            exit();
        }
        if (!self::$user->logIn(Input::post('username'), Input::post('password'), Input::post('remember'))) {
            Issue::error('Username or password was incorrect.');
            $this->view('login');
            exit();
        }
        Session::flash('success', 'You have been logged in.');
        if (Input::exists('rurl')) {
            Redirect::to(Input::post('rurl'));
        } else {
            Redirect::to('home/index');
        }
    }

    public function logout()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Log Out - {SITENAME}';
        self::$template->noIndex();
        if (!self::$isLoggedIn) {
            Issue::notice('You are not logged in.');
            exit();
        }
        self::$user->logOut();
        Session::flash('success', 'You have been logged out.');
        Redirect::to('home/index');
    }

    public function terms()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Terms and Conditions - {SITENAME}';
        self::$pageDescription = '{SITENAME} Terms and Conditions of use. Please use {SITENAME} safely.';
        $this->view('terms.page');
        exit();
    }
}
