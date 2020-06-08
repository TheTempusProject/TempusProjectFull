<?php
/**
 * models/blog.php
 *
 * This class is used for the manipulation of the blog database table.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Sanitize;
use TempusProjectCore\Core\DatabaseModel;

class Blog extends DatabaseModel
{
    public static $tableName = "posts";
    protected static $comment;
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
            'user',
            'comment'
        ];
        return $required;
    }
    
    /**
     * Tells the installer which types of integrations your model needs to install.
     *
     * @return array - Install flags
     */
    public static function installFlags()
    {
        $flags = [
            'installDB' => true,
            'installResources' => true
        ];
        return $flags;
    }
    
    /**
     * This function is used to install database structures needed for this model.
     *
     * @return bool - The status of the completed install
     */
    public static function installDB()
    {
        self::$db->newTable(self::$tableName);
        self::$db->addfield('author', 'int', '11');
        self::$db->addfield('created', 'int', '10');
        self::$db->addfield('edited', 'int', '10');
        self::$db->addfield('draft', 'int', '1');
        self::$db->addfield('title', 'varchar', '86');
        self::$db->addfield('content', 'text', '');
        self::$db->createTable();
        return self::$db->getStatus();
    }

    /**
     * Installs any resources needed for the model. Resources are generally
     * database entires or other structure data needed for the mdoel.
     *
     * @return bool - The status of the completed install
     */
    public static function installResources()
    {
        $fields = [
            'title' => 'Welcome',
            'content' =>'<p>This is just a simple message to say thank you for installing The Tempus Project. If you have any questions you can find everything through our website <a href="https://TheTempusProject.com">here</a>.</p>',
            'author' => 1,
            'created' => time(),
            'edited' => time(),
            'draft' => 0
            ];
        return self::$db->insert(self::$tableName, $fields);
    }
    
    public function newPost($title, $post, $draft)
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
        if (!self::$db->insert(self::$tableName, $fields)) {
            Debug::error("Blog Post: $data not updated: $fields");
            new customException('userUpdate');

            return false;
        }
        return true;
    }

    public function updatePost($id, $title, $content, $draft)
    {
        if (!isset(self::$log)) {
            self::$log = $this->model('log');
        }
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
        if (!self::$db->update(self::$tableName, $id, $fields)) {
            new CustomException('blogUpdate');
            Debug::error("Blog Post: $id not updated: $fields");

            return false;
        }
        self::$log->admin("Updated Blog Post: $id");
        return true;
    }

    public function preview($title, $content)
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

    public function filter($postArray, $params = [])
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        if (!isset(self::$comment)) {
            self::$comment = $this->model('comment');
        }
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
            if (isset($params['stripHtml']) && $params['stripHtml'] === true) {
                $instance->contentSummary = strip_tags($instance->content);
            }
            $instance->commentCount = self::$comment->count('blog', $instance->ID);
            $out[] = $instance;
            if (!empty($end)) {
                $out = $out[0];
                break;
            }
        }
        return $out;
    }

    public function archive($includeDraft = false)
    {
        $whereClause = [];
        $currentTimeUnix = time();
        $x = 0;
        $dataOut = [];
        $month = date("F", $currentTimeUnix);
        $year = date("Y", $currentTimeUnix);
        $previous = date("U", strtotime("$month 1st $year"));
        if ($includeDraft !== true) {
            $whereClause = ['draft', '=', '0', 'AND'];
        }
        while ($x <= 5) {
            $where =  array_merge($whereClause, ['created', '<=', $currentTimeUnix, 'AND', 'created', '>=', $previous]);
            $data = self::$db->get(self::$tableName, $where);
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

    public function recent($limit = null, $includeDraft = false)
    {
        $whereClause = [];
        if ($includeDraft !== true) {
            $whereClause = ['draft', '=', '0'];
        } else {
            $whereClause = '*';
        }
        if (empty($limit)) {
            $postData = self::$db->getPaginated(self::$tableName, $whereClause);
        } else {
            $postData = self::$db->getPaginated(self::$tableName, $whereClause, 'ID', 'DESC', [0, $limit]);
        }
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return $this->filter($postData->results());
    }

    public function listPosts($params = [])
    {
        if (isset($params['includeDrafts']) && $params['includeDrafts'] === true) {
            $whereClause = '*';
        } else {
            $whereClause = ['draft', '=', '0'];
        }
        $postData = self::$db->getPaginated(self::$tableName, $whereClause);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        if (isset($params['stripHtml']) && $params['stripHtml'] === true) {
            return $this->filter($postData->results(), ['stripHtml' => true]);
        }
        return $this->filter($postData->results());
    }

    public function byYear($year, $includeDraft = false)
    {
        if (!Check::id($year)) {
            Debug::info("Invalid Year");
            return false;
        }
        $whereClause = [];
        if ($includeDraft !== true) {
            $whereClause = ['draft', '=', '0', 'AND'];
        }
        $firstDayUnix = date("U", strtotime("first day of $year"));
        $lastDayUnix = date("U", strtotime("last day of $year"));
        $whereClause =  array_merge($whereClause, ['created', '<=', $lastDayUnix, 'AND', 'created', '>=', $firstDayUnix]);
        $postData = self::$db->getPaginated(self::$tableName, $whereClause);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return $this->filter($postData->results());
    }

    public function byAuthor($ID, $includeDraft = false)
    {
        if (!Check::id($ID)) {
            Debug::info("Invalid Author");
            return false;
        }
        $whereClause = [];
        if ($includeDraft !== true) {
            $whereClause = ['draft', '=', '0', 'AND'];
        }
        $whereClause =  array_merge($whereClause, ['author' => $ID]);
        $postData = self::$db->getPaginated(self::$tableName, $whereClause);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return $this->filter($postData->results());
    }

    public function byMonth($month, $year = 2018, $includeDraft = false)
    {
        if (!Check::id($month)) {
            Debug::info("Invalid Month");
            return false;
        }
        if (!Check::id($year)) {
            Debug::info("Invalid Year");
            return false;
        }
        $whereClause = [];
        if ($includeDraft !== true) {
            $whereClause = ['draft', '=', '0', 'AND'];
        }
        $firstDayUnix = date("U", strtotime("$month/01/$year"));
        $month = date("F", $firstDayUnix);
        $lastDayUnix = date("U", strtotime("last day of $month $year"));
        $whereClause =  array_merge($whereClause, ['created', '<=', $lastDayUnix, 'AND', 'created', '>=', $firstDayUnix]);
        $postData = self::$db->getPaginated(self::$tableName, $whereClause);
        if (!$postData->count()) {
            Debug::info("No Blog posts found.");

            return false;
        }
        return $this->filter($postData->results());
    }
}
