<?php
/**
 * Models/blog.php
 *
 * This class is used for the manipulation of the blog database table.
 *
 * @version 1.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */

namespace TheTempusProject\Models;

use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Check as Check;
use TempusProjectCore\Functions\Docroot as Docroot;
use TempusProjectCore\Classes\Sanitize as Sanitize;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Core\Updater as Updater;

class Blog extends Controller
{
    public function __construct()
    {
        Debug::log('Model Constructed: '.get_class($this));
    }

    /**
     * This function is used to install database structures and configuration
     * options needed for this model.
     *
     * @return boolean - The status of the completed install.
     */
    public static function install()
    {
        self::$db->newTable('posts');
        self::$db->addfield('author', 'int', '11');
        self::$db->addfield('created', 'int', '10');
        self::$db->addfield('edited', 'int', '10');
        self::$db->addfield('draft', 'int', '1');
        self::$db->addfield('title', 'varchar', '86');
        self::$db->addfield('content', 'text', '');
        self::$db->createTable();
        $fields = [
            'title' => 'Welcome',
            'content' =>'<p>This is just a simple message to say thank you for installing The Tempus Project. If you have any questions you can find everything through our website <a href="https://TheTempusProject.com">here</a>.</p>',
            'author' => 1,
            'created' => time(),
            'edited' => time(),
            'draft' => 0
            ];
        self::$db->insert('posts', $fields);
        return self::$db->getStatus();
    }

    /**
     * Function to delete the specified post.
     *
     * @param  int|array $ID the log ID or array of ID's to be deleted
     *
     * @return bool
     */
    public function delete($data)
    {
        foreach ($data as $instance) {
            if (!is_array($data)) {
                $instance = $data;
                $end = true;
            }
            if (!Check::id($instance)) {
                $error = true;
            }
            self::$db->delete('posts', ['ID', '=', $instance]);
            self::$log->admin("Deleted blog post: $instance");
            Debug::info("Post deleted: $instance");
            if (!empty($end)) {
                break;
            }
        }
        if (!empty($error)) {
            Debug::info('One or more invalid ID\'s.');
            return false;
        }
        return true;
    }
    
    public static function newPost($title, $post, $draft)
    {
        if (!Check::dataTitle($title)) {
            Debug::info("modelBlog: illegal title.");
            
            return false;
        }
        if ($draft === 'saveDraft') {
            $draft = 1;
        } else {
            $draft = 0;
        }
        $fields = [
            'author' => self::$activeUser->ID,
            'draft' => $draft,
            'created' => time(),
            'edited' => time(),
            'content' => Sanitize::rich($post),
            'title' => $title,
            ];
        if (!self::$db->insert('posts', $fields)) {
            Debug::error("Blog Post: $data not updated: $fields");
            new customException('userUpdate');

            return false;
        }
        return true;
    }
    public static function updatePost($id, $title, $content, $draft)
    {
        if (!Check::id($id)) {
            Debug::info("modelBlog: illegal ID.");
            
            return false;
        }
        if (!Check::dataTitle($title)) {
            Debug::info("modelBlog: illegal title.");
            
            return false;
        }
        if ($draft === 'saveDraft') {
            $draft = 1;
        } else {
            $draft = 0;
        }
        $fields = [
            'draft' => $draft,
            'edited' => time(),
            'content' => Sanitize::rich($content),
            'title' => $title,
            ];
        if (!self::$db->update('posts', $id, $fields)) {
            new CustomException('blogUpdate');
            Debug::error("Blog Post: $id not updated: $fields");

            return false;
        }
        self::$log->admin("Updated Blog Post: $id");
        return true;
    }

    public static function preview($title, $content)
    {
        if (!Check::dataTitle($title)) {
            Debug::info("modelBlog: illegal characters.");
            
            return false;
        }
        $fields = [
            'title' => $title,
            'content' => $content,
            'authorName' => self::$activeUser->username,
            'created' => time(),
            ];
        return (object) $fields;
    }

    public static function filterPost($postArray)
    {
        foreach ($postArray as $instance) {
            if (!is_object($instance)) {
                $instance = $postArray;
                $end = true;
            }
            $draft = '';
            $authorName = self::$user->getUsername($instance->author);
            $cleanPost = Sanitize::contentShort($instance->content);
            $postSpace = explode(" ", $cleanPost);
            $postLine = explode("\n", $cleanPost);
            // summary by words: 100
            $spaceSummary = implode(" ", array_splice($postSpace, 0, 100));
            // summary by lines: 5
            $lineSummary = implode("\n", array_splice($postLine, 0, 5));
            if (strlen($spaceSummary) < strlen($lineSummary)) {
                $contentSummary = $spaceSummary;
                if (count($postSpace, 1) <= 100) {
                    $contentSummary .= '... <a href="{base}blog/post/' . $instance->ID . '">Read More</a>';
                }
            } else {
                // @todo: need to refine this after testing
                $contentSummary = $lineSummary . '... <a href="{base}blog/post/' . $instance->ID . '">Read More</a>';
            }
            if ($instance->draft != '0') {
                $draft = ' <b>Draft</b>';
            }
            $instance->isDraft = $draft;
            $instance->authorName = $authorName;
            $instance->contentSummary = $contentSummary;
            $instance->commentCount = self::$comment->count('blog', $instance->ID);
            $out[] = $instance;
            if (!empty($end)) {
                $out = $out[0];
                break;
            }
        }
        return $out;
    }

    public static function find($id)
    {
        if (!Check::id($id)) {
            Debug::info("blog find: Invalid ID.");

            return false;
        }
        $postData = self::$db->get('posts', ['ID', '=', $id]);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return self::filterPost($postData->first());
    }
    public static function archive()
    {
        $currentTimeUnix = time();
        $x = 0;
        $dataOut = [];
        $month = date("F", $currentTimeUnix);
        $year = date("Y", $currentTimeUnix);
        $previous = date("U", strtotime("$month 1st $year"));
        while ($x <= 5) {
            $data = self::$db->get('posts', ['created', '<=', $currentTimeUnix, 'AND', 'created', '>=', $previous]);
            $x++;
            $month = date("m", $previous);
            $montht = date("F", $previous);
            $year = date("Y", $previous);
            if (!$data) {
                $count = 0;
            } else {
                $count = $data->count();
            }
            $dataOut[] = (object) [
                'count' => $count,
                'month' => $month,
                'year' => $year,
                'monthText' => $montht,
                ];
            $currentTimeUnix = $previous;
            $previous = date("U", strtotime("-1 months", $currentTimeUnix));
        }
        if (!$data) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return (object) $dataOut;
    }
    public static function recent($limit = null)
    {
        if (empty($limit)) {
            $postData = self::$db->getPaginated('posts', '*');
        } else {
            $postData = self::$db->get('posts', ['ID', '>', '0'], 'ID', 'DESC', [0, $limit]);
        }
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return self::filterPost($postData->results());
    }
    public static function listPosts($includeDrafts = false)
    {
        if ($includeDrafts === true) {
            $whereClause = '*';
        } else {
            $whereClause = ['draft', '=', '0'];
        }
        $postData = self::$db->getPaginated('posts', $whereClause);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return self::filterPost($postData->results());
    }
    public static function byYear($year)
    {
        if (!Check::id($year)) {
            Debug::info("Invalid Year");
            return false;
        }
        $firstDayUnix = date("U", strtotime("first day of $year"));
        $lastDayUnix = date("U", strtotime("last day of $year"));
        $postData = self::$db->get('posts', ['created', '<=', $lastDayUnix, 'AND', 'created', '>=', $firstDayUnix]);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return self::filterPost($postData->results());
    }
    public static function byAuthor($ID)
    {
        if (!Check::id($ID)) {
            Debug::info("Invalid Author");
            return false;
        }
        $postData = self::$db->getPaginated('posts', ['author' => $ID]);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return self::filterPost($postData->results());
    }
    public static function byMonth($month, $year = 2017)
    {
        if (!Check::id($month)) {
            Debug::info("Invalid Month");
            return false;
        }
        if (!Check::id($year)) {
            Debug::info("Invalid Year");
            return false;
        }
        $firstDayUnix = date("U", strtotime("$month/01/$year"));
        $month = date("F", $firstDayUnix);
        $lastDayUnix = date("U", strtotime("last day of $month $year"));
        $postData = self::$db->get('posts', ['created', '<=', $lastDayUnix, 'AND', 'created', '>=', $firstDayUnix]);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return self::filterPost($postData->results());
    }
}
