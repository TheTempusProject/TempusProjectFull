<?php
/**
 * Controllers/member.php
 *
 * This is the members controller.
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
use TempusProjectCore\Classes\Issue;

class Member extends Controller
{
    protected static $session;

    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$template->noIndex();
        if (!self::$isMember) {
            Issue::error('You do not have permission to view this page.');
            exit();
        }
        self::$session = $this->model('sessions');
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
