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

class Home extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$blog = $this->model('blog');
        self::$comment = $this->model('comment');
        self::$user = $this->model('user');
        self::$title = 'Admin - Home';
    }
    public function index()
    {
        $users = Template::standardView('admin.dashUsers', self::$user->recent(5));
        $comments = Template::standardView('admin.dashComments', self::$comment->recent('all', 5));
        $posts = Template::standardView('admin.dashPosts', self::$blog->recent(5));
        self::$template->set('userDash', $users);
        self::$template->set('blogDash', $posts);
        self::$template->set('commentDash', $comments);
        $this->view('admin.dash');
    }
}
