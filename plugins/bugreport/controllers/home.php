<?php
/**
 * controllers/home.php
 *
 * This is the home controller for the bug report plugin.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Redirect;
use TempusProjectCore\Classes\Session;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Issue;

class Home extends Controller
{
    protected static $session;
    protected static $bugreport;

    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$session = $this->model('sessions');
        self::$bugreport = $this->model('bugreport');
    }

    public function __destruct()
    {
        Debug::log('Controller Destructing: ' . get_class($this));
        self::$session->updatePage(self::$title);
        $this->build();
        Debug::closeAllGroups();
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
            $this->view('bugreport');
            exit();
        }
        if (!Check::form('bugreport')) {
            Issue::error('There was an error with your report.', Check::userErrors());
            $this->view('bugreport');
            exit();
        }
        self::$bugreport->create(self::$activeUser->ID, Input::post('url'), Input::post('ourl'), Input::post('repeat'), Input::post('entry'));
        Session::flash('success', 'Your Bug Report has been received. We may contact you for more information at the email address you provided.');
        Redirect::to('home/index');
    }
}
