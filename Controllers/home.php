<?php
/**
 * controllers/home.php
 *
 * This is the home controller.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Functions\Routes;
use TempusProjectCore\Classes\Redirect;
use TempusProjectCore\Classes\Session;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Issue;

class Home extends Controller
{
    protected static $session;
    protected static $recaptcha;
    protected static $user;

    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$session = $this->model('sessions');
        self::$subscribe = $this->model('subscribe');
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
        self::$title = '{SITENAME}';
        self::$pageDescription = 'This is the homepage of your new Tempus Project Installation. Thank you for installing. find more info at http://www.thetempusproject.com';
        $this->view('index');
        exit();
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
        self::$pageDescription = 'User Profile for ' . $user->username . ' - {SITENAME}';
        $this->view('user', $user);
    }

    public function login()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Portal - {SITENAME}';
        self::$pageDescription = 'Please log in to use {SITENAME} member features.';
        if (self::$isLoggedIn) {
            Issue::notice('You are already logged in. Please <a href="' . Routes::getAddress() . 'home/logout">click here</a> to log out.');
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
        self::$recaptcha = $this->model('recaptcha');
        if (!self::$recaptcha->verify(Input::post('g-recaptcha-response'))) {
            Issue::error('There was an error with your login.', self::$recaptcha->getErrors());
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
        $this->view('termsPage');
        exit();
    }
}
