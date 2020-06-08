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

use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Check;
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Comments extends AdminController
{
    
    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Comments';
        self::$comment = $this->model('comment');
    }
    public function edit($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (!Input::exists('submit')) {
            $this->view('admin.commentEdit', self::$comment->findById($data));
            exit();
        }
        if (!Check::form('editComment')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('admin.commentEdit', self::$comment->findById($data));
            exit();
        }
        if (self::$comment->update($data, Input::post('comment'))) {
            Issue::success('Comment updated');
        } else {
            $this->view('admin.commentEdit', self::$comment->findById($data));
            exit();
        }
        $this->index();
    }
    public function viewComment($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $commentData = self::$comment->findById($data);
        if ($commentData !== false) {
            $this->view('admin.comment', $commentData);
            exit();
        }
        Issue::error('Comment not found.');
        $this->index();    
    }
    public function delete($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if ($data == null) {
            if (!Input::exists('C_')) {
            $this->index();
            }
            $data = Input::post('C_');
        }
        if (!self::$comment->delete((array) $data)) {
            Issue::error('There was an error with your request.');
        } else {
            Issue::success('Comment has been deleted');
        }
        $this->index();
    }
    public function blog($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $commentData = self::$comment->display(25, 'blog', $data);
        if ($commentData !== false) {
            self::$template->set('count', self::$comment->count('blog', $data));
            $this->view('admin.blogComments', $commentData);
            exit();
        }
        Issue::notice('No comments found.');
        $this->index();
    }
    public function index($data = null)
    {
        $this->view('admin.commentRecent', self::$comment->recent());
        exit();
    }
}
