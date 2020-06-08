<?php
/**
 * controllers/admin/bugreport.php
 *
 * This is the bugreport admin controller.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Controllers;

use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Core\AdminController;

class Bugreport extends AdminController
{
    private static $bugreport;

    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$bugreport = $this->model('bugreport');
        self::$title = 'Admin - Bug Reports';
    }

    public function index($data = null)
    {
        Debug::log('Controller initiated: ' . __METHOD__ . '.');
        $this->view('bugreport.admin.list', self::$bugreport->list());
        exit();
    }

    public function view($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $reportData = self::$bugreport->findById($data);
        if ($reportData !== false) {
            $this->view('bugreport.admin.view', $reportData);
            exit();
        }
        Issue::error('Report not found.');
        $this->index();
    }

    public function delete($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (Input::exists('submit')) {
            $data = Input::post('BR_');
        }
        if (self::$bugreport->delete((array) $data)) {
            Issue::success('Bug Report Deleted');
        } else {
            Issue::error('There was an error with your request.');
        }
        $this->index();
    }

    public function clear($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$bugreport->empty();
        $this->index();
    }
}
