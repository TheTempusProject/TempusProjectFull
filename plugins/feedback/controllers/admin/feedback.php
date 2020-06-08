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
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Feedback extends AdminController
{
    
    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Feedback';
        self::$feedback = $this->model('feedback');
    }
    public function viewFeedback($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $this->view('admin.feedback', self::$feedback->get($data));
        exit();
    }
    public function delete($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (Input::exists('submit')) {
            $data = Input::post('F_');
        }
        if (self::$feedback->delete((array) $data)) {
            Issue::success('feedback deleted');
        } else {
            Issue::error('There was an error with your request.');
        }
        $this->index();
    }
    public function clear($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$feedback->clear();
        $this->index();
    }
    public function index($data = null)
    {
        $this->view('admin.feedbackList', self::$feedback->getList());
        exit();
    }
}
