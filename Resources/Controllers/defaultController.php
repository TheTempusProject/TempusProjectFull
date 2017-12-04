<?php
/**
 * Controllers/default.php
 *
 * This is the Default controller design.
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

class DefaultController extends Controller
{
    public function __construct()
    {
        self::$template->noFollow();
        self::$template->noIndex();
        self::$template->setTemplate('default');
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
        self::$title = 'Default Controller';
        $this->view('index');
        exit();
    }
}
