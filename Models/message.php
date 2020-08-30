<?php
/**
 * Models/message.php
 *
 * Houses all of the functions for the core messaging system.
 *
 * @version 3.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Classes\Check;
use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Permission;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Classes\DB;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Sanitize;
use TempusProjectCore\Core\Template;

class Message extends Controller
{
    protected static $user;
    protected $messages;
    protected $usernames;

    /**
     * The model constructor.
     */
    public function __construct()
    {
        Debug::log('Model Constructed: '.get_class($this));
    }

    /**
     * Returns the current model version.
     *
     * @return string - the correct model version
     */
    public static function modelVersion()
    {
        return '3.0.0';
    }
    
    /**
     * Returns an array of models required to run this model without error.
     *
     * @return array - An array of models
     */
    public static function requiredModels()
    {
        $required = [
            'user'
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
            'installPermissions' => true,
            'installConfigs' => false,
            'installResources' => false,
            'installPreferences' => false
        ];
        return $flags;
    }

    /**
     * This function is used to install database structures needed for this model.
     *
     * @return boolean - The status of the completed install
     */
    public static function installDB()
    {
        self::$db->newTable('messages');
        self::$db->addfield('userTo', 'int', '11');
        self::$db->addfield('userFrom', 'int', '11');
        self::$db->addfield('parent', 'int', '11');
        self::$db->addfield('sent', 'int', '10');
        self::$db->addfield('lastReply', 'int', '10');
        self::$db->addfield('senderDeleted', 'int', '1');
        self::$db->addfield('recieverDeleted', 'int', '1');
        self::$db->addfield('isRead', 'int', '1');
        self::$db->addfield('message', 'text', '');
        self::$db->addfield('subject', 'text', '');
        self::$db->createTable();
        return self::$db->getStatus();
    }

    /**
     * Install permissions needed for the model.
     *
     * @return bool - If the permissions were added without error
     */
    public static function installPermissions()
    {
        Permission::addPerm('sendMessage', false);
        return Permission::savePerms(true);
    }
    
    /**
     * This method will remove all the installed model components.
     *
     * @return bool - if the uninstall was completed without error
     */
    public static function uninstall()
    {
        Permission::removePerm('sendMessage', true);
        self::$db->removeTable('messages');
        return true;
    }
    
    public function loadInterface()
    {
        self::$template->set('MESSAGE_COUNT', $this->unreadCount());
        if ($this->unreadCount() > 0) {
            $messageBadge = Template::standardView('message.badge');
        } else {
            $messageBadge = '';
        }
        self::$template->set('MBADGE', $messageBadge);
        if (self::$isLoggedIn) {
            self::$template->set('RECENT_MESSAGES', Template::standardView('message.recent', $this->getInbox(5)));
        } else {
            self::$template->set('RECENT_MESSAGES', '');
        }
    }
    /**
     * Retrieves the most recent relative message in a thread
     *
     * @param  int $parent - the id of the parent message
     * @param  string $user   - the id of the relative user
     * @return object
     */
    public function getLatestMessage($parent, $user, $type = null)
    {
        if (!Check::id($parent)) {
            Debug::info('Invalid message ID');
            return false;
        }
        if (!Check::id($user)) {
            Debug::info('Invalid user ID');
            return false;
        }
        $messageData = self::$db->get('messages', ['ID', '=', $parent]);
        if ($messageData->count() == 0) {
            Debug::info('Message not found.');
            return false;
        }
        $message = $messageData->first();
        $params = ['parent', '=', $parent];
        if ($type !== null) {
            $params = array_merge($params, ["AND", $type, '=', $user]);
        }
        $messageData = self::$db->getPaginated('messages', $params, 'ID', 'DESC', [0, 1]);
        if ($messageData->count() != 0) {
            if ($messageData->first()->recieverDeleted == 0) {
                $message = $messageData->first();
            } else {
                $message->recieverDeleted = 1;
            }
        }
        return $message;
    }
    
    /**
     * This calls a view of the requested message.
     *
     * @param  int $ID - The message ID you are looking for.
     *
     * @return null
     */
    public function getThread($id, $markRead = false)
    {
        if (!Check::id($id)) {
            Debug::info('Invalid ID');
            return false;
        }
        $messageData = self::$db->get('messages', ['ID', '=', $id]);
        if ($messageData->count() == 0) {
            Debug::info('Message not found.');
            return false;
        }
        $message = $messageData->first();
        if ($message->userTo == self::$activeUser->ID) {
            $permissionCheck = 1;
            if ($message->recieverDeleted == 1) {
                Debug::info('User has already deleted this message.');
                return false;
            }
        }
        if ($message->userFrom == self::$activeUser->ID) {
            $permissionCheck = 1;
            if ($message->senderDeleted == 1) {
                Debug::info('User has already deleted this message.');
                return false;
            }
        }
        if (empty($permissionCheck)) {
            Debug::info('You do not have permission to view this message.');
            return false;
        }
        if ($message->parent != 0) {
            $find = $message->parent;
        } else {
            $find = $message->ID;
        }
        $messageData = self::$db->getPaginated('messages', ['ID', '=', $find, 'OR', 'Parent', '=', $find], 'ID', 'ASC')->results();
        self::$template->set('PID', $find);
        
        if ($markRead == true) {
            foreach ($messageData as $instance) {
                $this->markRead($instance->ID);
            }
        }
        return $this->processMessage($messageData);
    }

    public function getInbox($limit = null)
    {
        if (empty($limit)) {
            $limit = 10;
        }
        $limit = [0, $limit];
        $messageData = self::$db->get('messages', ['parent', '=', 0, "AND", 'userFrom', '=', self::$activeUser->ID, "OR", 'parent', '=', 0, "AND", 'userTo', '=', self::$activeUser->ID], 'ID', 'DESC', $limit);
        if ($messageData->count() == 0) {
            Debug::info('No messages found');
            return false;
        }
        $filters = [
            'importantUser' => self::$activeUser->ID,
            'deleted' => false,
            'type' => 'userTo'
        ];
        return $this->processMessage($messageData->results(), $filters);
    }

    /**
     * This function calls the view for the message outbox.
     *
     * @return null
     */
    public function getOutbox($limit = null)
    {
        if (empty($limit)) {
            $limit = 10;
        }
        $limit = [0, $limit];
        $messageData = self::$db->get('messages', ['parent', '=', 0, "AND", 'userFrom', '=', self::$activeUser->ID], 'ID', 'DESC', $limit);
        if ($messageData->count() == 0) {
            Debug::info('No messages found');
            return false;
        }
        $filters = [
            'importantUser' => self::$activeUser->ID,
            'deleted' => false,
            'type' => 'userFrom'
        ];
        return $this->processMessage($messageData->results(), $filters);
    }

    /**
     * This function is to prep our messages for display. An array of raw messages
     * sent through this function will automatically have all the user ID's filter
     * into actual usernames.
     *
     * @param  $messageArray - This is an array of messages that need to be processed.
     *
     * @return array - It will return the same message array after being processed.
     *
     * @todo  add filtering for BB-code.
     */
    private function processMessage($messageArray, $filters = [])
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        $out = null;
        foreach ($messageArray as $message) {
            if (isset($filters['type']) && isset($filters['importantUser'])) {
                $type = $filters['type'];
            } else {
                $type = null;
            }
            if (isset($filters['importantUser'])) {
                $user = $filters['importantUser'];
            } else {
                $user = self::$activeUser->ID;
            }
            if ($message->parent == 0) {
                $last = $this->getLatestMessage($message->ID, $user, $type);
            } else {
                $last = $message;
            }
            if ($type != null && $message->$type != $user && $last->$type != $user) {
                continue;
            }
            if (isset($filters['deleted']) && $filters['deleted'] == false) {
                if ($type == 'userFrom') {
                    if ($last->senderDeleted == 1) {
                        continue;
                    }
                }
                if ($type == 'userTo') {
                    if ($last->recieverDeleted == 1) {
                        continue;
                    }
                }
            }
            $messageOut = (array) $message;
            $short = explode(" ", Sanitize::contentShort($message->message));
            $summary = implode(" ", array_splice($short, 0, 25));
            if (count($short, 1) >= 25) {
                $messageOut['summary'] = $summary . '...';
            } else {
                $messageOut['summary'] = $summary;
            }
            if ($last->isRead == 0) {
                $messageOut['unreadBadge'] = self::$template->standardView('message.unreadBadge');
            } else {
                $messageOut['unreadBadge'] = '';
            }
            $messageOut['fromAvatar'] = self::$user->getAvatar($message->userFrom);
            $messageOut['userTo'] = self::$user->getUsername($message->userTo);
            $messageOut['userFrom'] = self::$user->getUsername($message->userFrom);
            $out[] = (object) $messageOut;
        }
        return $out;
    }

    /**
     * Function to check input and save messages to the DB.
     *
     * @param  string $data - Username of the person receiving the sent message.
     *
     * @return function
     */
    public function newThread($to, $subject, $message)
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        if (!Check::usernameExists($to)) {
            Debug::info('Message->sendMessage: User not found.');
            return false;
        }
        $fields = [
            'userTo' => self::$user->getID($to),
            'userFrom' => self::$activeUser->ID,
            'parent' => 0,
            'sent' => time(),
            'lastReply' => time(),
            'senderDeleted' => 0,
            'recieverDeleted' => 0,
            'isRead' => 0,
            'subject' => $subject,
            'message' => $message,
        ];
        if (!self::$db->insert('messages', $fields)) {
            new CustomException('messageSend');
            return false;
        }
        return true;
    }

    public function unreadCount()
    {
        if (empty(self::$activeUser->ID)) {
            return 0;
        }
        $result = self::$db->get('messages', ['userTo', '=', self::$activeUser->ID, "AND", 'isRead', '=', 0, "AND", 'parent', '=', 0, "AND", 'recieverDeleted', '=', 0]);
        return $result->count();
    }

    public function hasPermission($id)
    {
        if (!Check::id($id)) {
            Debug::info('Invalid ID');
            return false;
        }
        $messageData = self::$db->get('messages', ['ID', '=', $id]);
        if ($messageData->count() == 0) {
            Debug::info('Message not found.');
            return false;
        }
        $message = $messageData->first();
        if ($message->userTo != self::$activeUser->ID && $message->userFrom != self::$activeUser->ID) {
            return false;
        }
        return true;
    }

    /**
     * Marks a message as read. This is setup to only work
     * if the message was sent to the active user.
     *
     * @param  int - The message ID you are marking as read.
     *
     * @return bool
     */
    public function markRead($id)
    {
        if (!Check::id($id)) {
            Debug::info('Invalid ID');
            return false;
        }
        $result = self::$db->get('messages', ['ID', '=', $id, 'AND', 'userTo', '=', self::$activeUser->ID, 'AND', 'isRead', '=', '0']);
        if ($result->count() == 0) {
            Debug::info('Failed to mark message as read.');
            return false;
        }
        if (!self::$db->update('messages', $id, ['isRead' => 1])) {
            Debug::error('Failed to mark message as read.');
            return false;
        }
        return true;
    }
    public function newMessageReply($id, $message)
    {
        if (!$this->hasPermission($id)) {
            Debug::info("Permission Denied.");
            return false;
        }
        $messageData = self::$db->get('messages', ['ID', '=', $id])->first();
        if ($messageData->userTo == self::$activeUser->ID) {
            $recipient = $messageData->userFrom;
        } else {
            $recipient = $messageData->userTo;
        }
        if ($recipient === self::$activeUser->ID) {
            Debug::info('Cannot send messages to yourself');
            return false;
        }
        if (!self::$db->update('messages', $messageData->ID, ['lastReply' => time()])) {
            new CustomException('messagesReplyUpdate');
            return false;
        }
        $fields = [
            'senderDeleted' => 0,
            'recieverDeleted' => 0,
            'isRead' => 0,
            'userTo' => $recipient,
            'userFrom' => self::$activeUser->ID,
            'message' => $message,
            'subject' => 're: ' . $messageData->subject,
            'sent' => time(),
            'parent' => $messageData->ID,
            'lastReply' => time()
        ];
        if (!self::$db->insert('messages', $fields)) {
            new CustomException('messagesReplySend');
            return false;
        }
        return true;
    }
    public function messageTitle($id)
    {
        if (!$this->hasPermission($id)) {
            Debug::info("Permission Denied.");
            return false;
        }
        $message = self::$db->get('messages', ['ID', '=', $id])->first();
        return $message->subject;
    }
    /**
     * Function to delete messages from the DB.
     *
     * @param  int $data - The ID of the message you are trying to delete.
     * @todo  - done at 5 am after no sleep. This can be simplified a lot, i just wanted a working solution ASAP
     * @return bool
     */
    public function deleteMessage($data)
    {
        foreach ($data as $instance) {
            if (!is_array($data)) {
                $instance = $data;
                $end = true;
            }
            if (!Check::id($instance)) {
                $error = true;
            }
            if (!$this->hasPermission($instance)) {
                Debug::info("Permission Denied.");
                return false;
            }
            $message = self::$db->get('messages', ['ID', '=', $instance])->first();
            if ($message->userTo == self::$activeUser->ID) {
                $fields = ['recieverDeleted' => '1'];
            } else {
                $fields = ['senderDeleted' => '1'];
            }
            if (!self::$db->update('messages', $instance, $fields)) {
                $error = true;
            }
            Debug::info("message Deleted: $instance");
            if (!empty($end)) {
                break;
            }
        }
        if (!empty($error)) {
            Debug::info('There was an error deleting one or more messages.');
            return false;
        }
        return true;
    }
}
