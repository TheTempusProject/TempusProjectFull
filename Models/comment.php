<?php
/**
 * Models/comment.php
 *
 * This class is used for the creation, retrieval, and manipulation
 * of the comments table.
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

use TempusProjectCore\Classes\Check as Check;
use TempusProjectCore\Classes\Code as Code;
use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Classes\Session as Session;
use TempusProjectCore\Classes\Cookie as Cookie;
use TempusProjectCore\Classes\Log as Log;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Email as Email;
use TempusProjectCore\Core\Installer as Installer;

class Comment extends Controller
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
        self::$db->newTable('comments');
        self::$db->addfield('author', 'int', '11');
        self::$db->addfield('contentID', 'int', '11');
        self::$db->addfield('created', 'int', '10');
        self::$db->addfield('edited', 'int', '10');
        self::$db->addfield('approved', 'int', '1');
        self::$db->addfield('contentType', 'varchar', '32');
        self::$db->addfield('content', 'text', '');
        self::$db->createTable();
        return self::$db->getStatus();
    }
    /**
     * Retrieves a comment by its ID and parses it.
     *
     * @param  integer $id - The ID of the comment you are
     *                       trying to retrieve.
     *
     * @return object - The parsed comment db entry.
     */
    public static function findById($id)
    {
        if (!Check::id($id)) {
            Debug::info("comments: illegal ID.");
            
            return false;
        }
        $commentData = self::$db->get('comments', ['ID', '=', $id]);
        if (!$commentData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return self::filterComments($commentData->results());
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
            self::$db->delete('comments', ['ID', '=', $instance]);
            self::$log->admin("Deleted comment: $instance");
            Debug::info("Comment deleted: $instance");
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
    public static function count($contentType, $contentID)
    {
        if (!Check::id($contentID)) {
            Debug::info("Comments: illegal ID.");
            
            return false;
        }
        if (!Check::dataTitle($contentType)) {
            Debug::info("Comments: illegal Type.");
            
            return false;
        }
        $where = ['contentType', '=', $contentType, 'AND', 'contentID', '=', $contentID];
        $data = self::$db->get('comments', $where);
        if (!$data->count()) {
            Debug::info("No comments found.");

            return 0;
        }
        return $data->count();
    }
    public static function display($displayCount, $contentType, $contentID)
    {
        if (!Check::id($contentID)) {
            Debug::info("Comments: illegal ID.");
            
            return false;
        }
        if (!Check::dataTitle($contentType)) {
            Debug::info("Comments: illegal Type.");
            
            return false;
        }
        $where = ['contentType', '=', $contentType, 'AND', 'contentID', '=', $contentID];
        $commentData = self::$db->get('comments', $where, 'created', 'DESC', [0, $displayCount]);
        if (!$commentData->count()) {
            Debug::info("No comments found.");

            return false;
        }
        return self::filterComments($commentData->results());
    }
    public static function update($data, $comment)
    {
        if (!Check::id($data)) {
            Debug::info("Comments: illegal ID.");
            
            return false;
        }
        $fields = [
            'edited' => time(),
            'content' => $comment,
            'approved' => 1,
            ];
        if (!self::$db->update('comments', $data, $fields)) {
            new CustomException('commentUpdate');
            Debug::error("Post: $data not updated: $fields");

            return false;
        }
        self::$log->admin("Updated Comment: $data");
        return true;
    }
    public static function create($contentType, $contentID, $comment)
    {
        if (!Check::id($contentID)) {
            Debug::info("Comments: illegal ID.");
            
            return false;
        }
        if (!Check::dataTitle($contentType)) {
            Debug::info("Comments: illegal Type.");
            
            return false;
        }
        $fields = [
            'author' => self::$activeUser->ID,
            'edited' => time(),
            'created' => time(),
            'content' => $comment,
            'contentType' => $contentType,
            'contentID' => $contentID,
            'approved' => 0,
            ];
        if (!self::$db->insert('comments', $fields)) {
            new CustomException('newComment');
            Debug::error("Comments: $data not created: $fields");

            return false;
        }
        return true;
    }
    public static function filterComments($data)
    {
        foreach ($data as $instance) {
            if (!is_object($instance)) {
                $instance = $data;
                $end = true;
            }
            $authorName = self::$user->getUsername($instance->author);
            $authorAvatar = self::$user->getAvatar($instance->author);
            switch ($instance->contentType) {
                case 'blog':
                    $content = self::$blog->find($instance->contentID);
                    if ($content !== false) {
                        $title = $content->title;
                    }
                    break;
            }
            if (!isset($title)) {
                $title = 'Unknown';
            }
            $instance->avatar = $authorAvatar;
            $instance->authorName = $authorName;
            $instance->contentTitle = $title;
            $out[] = $instance;
            if (!empty($end)) {
                $out = $out[0];
                break;
            }
        }
        return $out;
    }
    public static function recent($contentType = 'all', $limit = null)
    {
        switch ($contentType) {
            case 'blog':
                if (empty($limit)) {
                    $commentData = self::$db->getPaginated('comments', '*');
                } else {
                    $commentData = self::$db->get('comments', ['contentType', '=', $contentType], 'created', 'DESC', [0, $limit]);
                }
                break;
            default:
                if (empty($limit)) {
                    $commentData = self::$db->getPaginated('comments', '*');
                } else {
                    $commentData = self::$db->get('comments', ['ID', '>', '0'], 'created', 'DESC', [0, $limit]);
                }
                break;
        }
        
        if (!$commentData->count()) {
            Debug::info("No comments found.");

            return false;
        }
        return self::filterComments($commentData->results());
    }
}
