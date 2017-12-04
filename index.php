<?php
/**
 * index.php
 *
 * Using the .htaccess rerouting: all traffic should be directed to/through index.php.
 * In this file we initiate all models we will need, authenticate sessions, set
 * template objects, and call appload to initialize the appropriate controller/method.
 *
 * @version 1.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */

namespace TheTempusProject;

use TempusProjectCore\Classes\Pagination as Pagination;
use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Functions\Docroot as Docroot;
use TempusProjectCore\Core\Template as Template;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Issue as Issue;
use TempusProjectCore\App as App;

require_once "init.php";

class Appload extends Controller
{
    // Prevents the application from initiating twice.
    private static $initiated = false;
    
    /**
     * The constructor takes care of everything that we will need before
     * finally calling appload to instantiate the appropriate controller/method.
     *
     * @param string $urlDirected - A custom url string to be used when initiating
     *                              the application.
     */
    public function __construct($urlDirected = null)
    {
        if (self::$initiated === true) {
            return;
        } else {
            self::$initiated = true;
        }
        parent::__construct();

        // Authenticate our session
        self::$session->authenticate();

        // Populate some of the Template Data
        self::$template->set('SITENAME', Config::get('main/name'));
        self::$template->set('RURL', Docroot::getUrl());
        self::$template->addFilter('member', '#{MEMBER}(.*?){/MEMBER}#is', self::$isMember);
        self::$template->addFilter('mod', '#{MOD}(.*?){/MOD}#is', self::$isMod);
        self::$template->addFilter('admin', '#{ADMIN}(.*?){/ADMIN}#is', self::$isAdmin);
        self::$message->loadInterface();
        
        // This also needs to be moved somewhere else
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
if (stripos($_SERVER['REQUEST_URI'], 'install.php')) {
    $appload = new Appload('install/index');
} else {
    $appload = new Appload();
}
Debug::gend();
