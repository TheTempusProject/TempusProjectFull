<?php
/**
 * Models/comment.php
 *
 * This class is used for the creation, retrieval, and manipulation
 * of the comments table.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Code;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Classes\DB;
use TempusProjectCore\Core\Template;
use TempusProjectCore\Core\DatabaseModel;

class Comment extends DatabaseModel
{
    public static $tableName = "xxxxxx";
    protected static $user;

    /**
     * Returns the current model version.
     *
     * @return string - the correct model version
     */
    public static function modelVersion()
    {
        return '1.0.0';
    }

    /**
     * Returns an array of models required to run this model without error.
     *
     * @return array - An array of models
     */
    public static function requiredModels()
    {
        $required = [
            'log',
            'user'
        ];
        return $required;
    }

    /**
     * This function is used to install database structures needed for this model.
     *
     * @return boolean - The status of the completed install
     */
    public static function installDB()
    {
        self::$db->newTable(self::$tableName);
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
    public function findById($id)
    {
        if (!Check::id($id)) {
            Debug::info("comments: illegal ID.");
            
            return false;
        }
        $commentData = self::$db->get('comments', ['ID', '=', $id]);
        if (!$commentData->count()) {
            Debug::info("No comments found.");

            return false;
        }
        return $this->filterComments($commentData->first());
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
        if (!isset(self::$log)) {
            self::$log = $this->model('log');
        }
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
    public function count($contentType, $contentID)
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
    public function display($displayCount, $contentType, $contentID)
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
        return $this->filterComments($commentData->results());
    }
    public function update($data, $comment)
    {
        if (!isset(self::$log)) {
            self::$log = $this->model('log');
        }
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
    public function create($contentType, $contentID, $comment)
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
    public function filterComments($data)
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        foreach ($data as $instance) {
            if (!is_object($instance)) {
                $instance = $data;
                $end = true;
            }
            if (self::$isAdmin || (self::$isLoggedIn && $instance->author == self::$activeUser->ID)) {
                $instance->commentControl = Template::standardView('commentControl', ["ID" => $instance->ID]);
            } else {
                $instance->commentControl = "";
            }
            $authorName = self::$user->getUsername($instance->author);
            $authorAvatar = self::$user->getAvatar($instance->author);
            if (!isset($title)) {
                $title = 'Unknown';
            }
            $instance->avatar = $authorAvatar;
            $instance->authorName = $authorName;
            $instance->contentTitle = $title;
            $out[] = $instance;
            if (!empty($end)) {
                $out = $instance;
                break;
            }
        }
        return $out;
    }


    public function recent($contentType = 'all', $limit = null)
    {
        if ($contentType === 'all') {
            $where = ['ID', '>', '0'];
        } else {
            $where = ['contentType', '=', $contentType];
        }

        if (empty($limit)) {
            $commentData = self::$db->getPaginated('comments', $where, 'created', 'DESC');
        } else {
            $commentData = self::$db->get('comments', $where, 'created', 'DESC', [0, $limit]);
        }
        
        if (!$commentData->count()) {
            Debug::info("No comments found.");

            return false;
        }
        return $this->filterComments($commentData->results());
    }
}
