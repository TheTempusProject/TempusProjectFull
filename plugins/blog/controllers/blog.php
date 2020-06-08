<?php
/**
 * Controllers/blog.php
 *
 * This is the blog controller.
 *
 * @todo  This needs a refactor along with/following
 *        refactoring of the blog and comments models
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
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Classes\DB;
use TempusProjectCore\Classes\Session;
use TempusProjectCore\Classes\Cookie;
use TempusProjectCore\Classes\Log;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Email;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Redirect;

class Blog extends Controller
{
    protected static $session;
    protected static $blog;
    protected static $comment;

    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$template->setTemplate('blog');
        self::$session = $this->model('sessions');
        self::$blog = $this->model('blog');
        self::$comment = $this->model('comment');
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
        self::$title = '{SITENAME} Blog';
        self::$pageDescription = '{SITENAME} blog';
        $this->view('blog.postList', self::$blog->listPosts());
        exit();
    }

    public function rss()
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = '{SITENAME} Feed';
        self::$pageDescription = '{SITENAME} blog RSS feed.';
        self::$template->setTemplate('rss');
        header('Content-Type: text/xml');
        $this->view('blog.rss', self::$blog->listPosts(['stripHtml' => true]));
        exit();
    }

    public function comments($sub, $data)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (!self::$isLoggedIn) {
            Issue::notice('You must be logged in to do that.');
            exit();
        }
        switch ($sub) {
            case 'post':
                $post = self::$blog->find($data);
                if (!Check::form('newComment')) {
                    Issue::error('There was a problem posting your comment.', Check::userErrors());
                    break;
                }
                self::$comment->create('blog', $post->ID, Input::post('comment'));
                Issue::success('Comment posted');
                break;
             
            case 'edit':
                $comment = self::$comment->findById($data);
                if (!self::$isLoggedIn || (!self::$isAdmin && $comment->author != self::$activeUser->ID)) {
                    Issue::error('You do not have permission to edit this comment');
                    $data = $comment->contentID;
                    break;
                }
                if (!Input::exists('submit')) {
                    $this->view('admin.commentEdit', self::$comment->findById($data));
                    exit();
                }
                if (!Check::form('editComment')) {
                    Issue::error('There was a problem editing your comment.', Check::userErrors());
                    $this->view('admin.commentEdit', self::$comment->findById($data));
                    exit();
                }
                if (!self::$comment->update($data, Input::post('comment'))) {
                    Issue::error('There was a problem editing your comment.', Check::userErrors());
                    $data = $comment->contentID;
                    break;
                }
                Issue::success('Comment updated');
                $data = self::$comment->findById($data)->author;
                break;
            
            case 'delete':
                $comment = self::$comment->findById($data);
                if (!self::$isLoggedIn || (!self::$isAdmin && $comment->author != self::$activeUser->ID)) {
                    Issue::error('You do not have permission to edit this comment');
                    break;
                }
                if (!self::$comment->delete((array) $data)) {
                    Issue::error('There was an error with your request.');
                } else {
                    Issue::success('Comment has been deleted');
                }
                break;
        }
        $this->post($data);
    }

    public function post($data)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Blog Post';
        self::$template->set('POST_ID', $data);
        if (self::$isLoggedIn) {
            self::$template->set('NEWCOMMENT', self::$template->standardView('blog.commentNew'));
        } else {
            self::$template->set('NEWCOMMENT', '');
        }
        $post = self::$blog->find($data);
        self::$template->set('count', self::$comment->count('blog', $post->ID));
        self::$template->set('COMMENTS', self::$template->standardView('commentList', self::$comment->display(10, 'blog', $post->ID)));
        $this->view('blog.post', $post);
        exit();
    }

    public function author($data)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Posts by author - {SITENAME}';
        self::$pageDescription = '{SITENAME} blog posts easily and conveniently sorted by author.';
        $this->view('blog.postList', self::$blog->byAuthor($data));
        exit();
    }

    public function month($month, $year = 2017)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Posts By Month - {SITENAME}';
        self::$pageDescription = '{SITENAME} blog posts easily and conveniently sorted by month.';
        $this->view('blog.postList', self::$blog->byMonth($month, $year));
        exit();
    }

    public function year($year)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$title = 'Posts by Year - {SITENAME}';
        self::$pageDescription = '{SITENAME} blog posts easily and conveniently sorted by years.';
        $this->view('blog.postList', self::$blog->byYear($year));
        exit();
    }
}
