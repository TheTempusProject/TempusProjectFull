<?php
/**
 * Controllers/member.php
 *
 * This is the members controller.
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
use TempusProjectCore\Classes\Issue as Issue;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Hash as Hash;
use TempusProjectCore\Classes\Code as Code;

class Member extends Controller
{
    public function __construct()
    {
        self::$template->noIndex();
        if (!self::$isMember) {
            Issue::error('You do not have permission to view this page.');
            exit();
        }
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
        self::$title = 'Members Area';
        exit();
    }
}
