<?php
/**
 * Controllers/Admin/.php
 *
 * This is the xxxxxx controller.
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

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Hash;
use TempusProjectCore\Classes\Code;
use TempusProjectCore\Core\Template;
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Logs extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Logs';
        self::$log = $this->model('log');
        Template::addFilter('logMenu', "#<ul id=\"log-menu\" class=\"collapse\">#is", "<ul id=\"log-menu\" class=\"\">", true);
    }
    public function index($data = null)
    {   
        $this->view('admin.logErrorList', self::$log->errorList());
        $this->view('admin.logAdminList', self::$log->adminList());
        $this->view('admin.logLoginList', self::$log->loginList());
        exit();
    }
    public function delete($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (self::$log->delete($data) === true) {
            Issue::success('Log Deleted');
        } else {
            Issue::error('There was an error with your request.');
        }
        $this->index();
    }
    public function viewLog($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $logData = self::$log->getLog($data);
        if ($logData !== false) {
            $this->view('admin.log', self::$log->getLog($data));
            exit();
        }
        Issue::error('Log not found.');
        $this->index();
    }
}
