<?php
/**
 * controllers/home.php
 *
 * This is the home controller for the XXXXX plugin.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Redirect;
use TempusProjectCore\Classes\Session;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Email;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Issue;

class Home extends Controller
{
    protected static $session;
    protected static $feedback;

    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$session = $this->model('sessions');
        self::$feedback = $this->model('feedback');
    }

    public function __destruct()
    {
        Debug::log('Controller Destructing: ' . get_class($this));
        self::$session->updatePage(self::$title);
        $this->build();
        Debug::closeAllGroups();
    }

    public function feedback()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Feedback - {SITENAME}';
        self::$pageDescription = 'At {SITENAME}, we value our users\' input. You can provide any feedback or suggestions using this form.';
        if (!Input::exists()) {
            $this->view('feedback');
            exit();
        }
        if (!Check::form('feedback')) {
            Issue::error('There was an error with your form, please check your submission and try again.', Check::userErrors());
            $this->view('feedback');
            exit();
        }
        self::$feedback->create(Input::post('name'), Input::post('feedbackEmail'), Input::post('entry'));
        Session::flash('success', 'Thank you! Your feedback has been received.');
        Redirect::to('home/index');
    }

}
