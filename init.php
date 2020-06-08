<?php
/**
 * init.php
 *
 * The purpose of this file is to have a sequence of events that
 * will always take place before any other classes or functions
 * start loading. Its basically Gandalf, if you don't meet these
 * requirements, "YOU SHALL NOT PASS".
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject;

use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Functions\Routes;
use TempusProjectCore\Classes\Redirect;

// session_start();

define('APP_SPACE', __NAMESPACE__);

$mods = apache_get_modules();
if (!in_array('mod_rewrite', $mods)) {
    echo file_get_contents('App/Views/Errors/Rewrite.php');
    exit();
}
if (!file_exists('vendor/autoload.php')) {
    echo file_get_contents('views/errors/composer.php');
    exit();
}
require_once 'vendor/autoload.php';

// set_exception_handler('TempusProjectCore\\Functions\\ExceptionHandler::ExceptionHandler');

/**
 * This will check for the htaccess file since it controls
 * the routing of the site. If not found it will redirect to
 * our error page for a broken installation.
 *
 * @todo  - Deal with this differently.
 */
$htaccessPath = Routes::getFull().'.htaccess';
$installPath = Routes::getFull().'install.php';

if (!file_exists($htaccessPath) && !file_exists($installPath)) {
    echo file_get_contents('App/Views/Errors/533.php');
    exit();
}
