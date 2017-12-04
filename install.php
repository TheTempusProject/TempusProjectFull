<?php
/**
 * install.php
 *
 * This is the install controller for the application.
 * After completion: YOU SHOULD DELETE THIS FILE.
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

require_once 'index.php';

use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Core\Installer as Installer;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Classes\Session as Session;
use TempusProjectCore\Classes\Cookie as Cookie;
use TempusProjectCore\Classes\Redirect as Redirect;
use TempusProjectCore\Classes\Log as Log;
use TempusProjectCore\Classes\Check as Check;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Email as Email;
use TempusProjectCore\Classes\Pagination as Pagination;
use TempusProjectCore\Classes\Issue as Issue;
use TempusProjectCore\Classes\Hash as Hash;
use TempusProjectCore\Classes\Token as Token;
use TempusProjectCore\Functions\Docroot as Docroot;
use TempusProjectCore\Classes\CustomException as CustomException;

class Install extends Controller
{
    public function __construct()
    {
        self::$template->noIndex();
        self::$template->noFollow();
        Debug::group("Controller: " . get_class($this), 1);
    }
    public function __destruct()
    {
        Debug::log('Controller Destructing: '.get_class($this));
        Debug::gend();
        $this->build();
    }
    /**
     * Currently this is the only page for this installer. This will change
     * in the future if/once the installer needs more time to complete.
     */
    public function index()
    {
        Debug::log("Controller initiated: " . __METHOD__ . '.');
        self::$pageDescription = 'This is the installer for the tempus project.';
        self::$title = 'TTP Installer';
        Debug::log('Installer Process Initiated');
        $form = (object) [
            'dbHost'  => Input::postNull('dbHost'),
            'dbName'  => Input::postNull('dbName'),
            'dbUsername'  => Input::postNull('dbUsername'),
            'name'  => Input::postNull('siteName'),
            'user'  => Input::postNull('newUsername'),
            'email'  => Input::postNull('email'),
            'email2' => Input::postNull('email2')
        ];
        if (!Input::exists()) {
            $this->view('install', $form);
            exit();
        }
        if (!Check::form('install')) {
            Issue::error('There was an error with the Installation.', Check::userErrors());
            $this->view('install', $form);
            exit();
        }
        if (!Config::generateConfig()) {
            Issue::error('Config file already exists so the installer has been halted. If there was an error with installation, please delete App/config.php manually, and try Again.');
            exit();
        }
        $installer = new Installer;
        if (!$installer->installFolder()) {
            Issue::error('There was an error with the Installation.', $installer->getErrors());
        }
        $installer->checkHtaccess(true);
        if (!self::$user->create([
            'username' => Input::post('newUsername'),
            'password' => Hash::make(Input::post('password')),
            'email' => Input::post('email'),
            'registered' => time(),
            'confirmed' => 1,
            'terms' => 1,
            'userGroup' => 1,
        ])) {
            Issue::error('There was an error creating the admin user.');
        }
        Email::send(Input::post('email'), 'install', null, ['template' => true]);
        Session::flash('success', 'Install has successfully completed!');
        Redirect::to('home/index');
        exit();
    }
}
