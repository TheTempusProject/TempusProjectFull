<?php
/**
 * index.php
 *
 * Using the .htaccess rerouting: all traffic should be directed to/through index.php.
 * In this file we initiate all models we will need, authenticate sessions, set
 * template objects, and call appload to initialize the appropriate controller/method.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject;

use TempusProjectCore\Classes\Pagination;
use TempusProjectCore\Core\Controller;
use TempusProjectCore\Functions\Routes;
use TempusProjectCore\Core\Template;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Redirect;
use TempusProjectCore\App;

require_once "init.php";

class Appload extends Controller
{
    protected static $initiated = false;
    protected static $session;
    protected static $message;
    /**
     * The constructor takes care of everything that we will need before
     * finally calling appload to instantiate the appropriate controller/method.
     *
     * @param string $urlDirected - A custom url for initiating the app
     */
    public function __construct($urlDirected = null)
    {
        // Prevents the application from initiating twice.
        if (self::$initiated === true) {
            return;
        } else {
            self::$initiated = true;
        }
        parent::__construct();
        self::$session = $this->model('sessions');
        self::$message = $this->model('message');

        // Authenticate our session
        self::$session->authenticate();

        // Populate some of the Template Data
        self::$template->set('SITENAME', Config::get('main/name'));
        self::$template->set('RECAPTCHA_SITE_KEY', Config::get('recaptcha/siteKey'));
        self::$template->set('RECAPTCHA', '');
        // self::$template->set('RECAPTCHA', Template::standardView('recaptcha'));
        self::$template->set('RURL', Routes::getUrl());
        self::$template->addFilter('member', '#{MEMBER}(.*?){/MEMBER}#is', (self::$isMember ? '$1' : ''), true);
        self::$template->addFilter('mod', '#{MOD}(.*?){/MOD}#is', (self::$isMod ? '$1' : ''), true);
        self::$template->addFilter('admin', '#{ADMIN}(.*?){/ADMIN}#is', (self::$isAdmin ? '$1' : ''), true);
        self::$message->loadInterface();
        
        // This needs to be moved somewhere else if possible
        if (self::$isAdmin) {
            if (file_exists(self::$location . "install.php")) {
                if (Debug::status()) {
                    Debug::warn("You have not removed the installer yet.");
                } else {
                    Issue::error("You have not removed the installer. This is a security risk that should be corrected immediately.");
                }
            }
        }
        
        if (!empty($urlDirected)) {
            $app = new App($urlDirected);
        } else {
            $app = new App();
        }
    }
}

/**
 * Instantiate the new instance of our application.
 *
 * You can add to the conditional for any pages that you want to have
 * access to outside of the typical .htaccess redirect method.
 */
Debug::group('Initiating TTP Application');
if (Input::exists('tracking')) {
    switch (Input::get('tracking')) {
        case 'pixel':
            $appload = new Appload('Tracking/pixel');
            redirect::to('Images/pixel.png');
            break;
        default:
            $appload = new Appload('Tracking/index');
            break;
    }
} elseif (Input::exists('error')) {
    switch (Input::get('error')) {
        case 'image404':
            Debug::error('Image not found');
            redirect::to('Images/imageNotFound.png');
            break;
        case 'upload404':
            # code...
            break;
        case '404':
            # code...
            break;
        
        default:
            $appload = new Appload('error/' . Input::exists('error'));
            break;
    }
} elseif (stripos($_SERVER['REQUEST_URI'], 'install.php')) {
    $appload = new Appload('install/index');
} else {
    $appload = new Appload();
}
Debug::gend();
