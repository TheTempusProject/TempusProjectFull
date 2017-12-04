<?php
/**
 * Models/message.php
 *
 * Houses all of the functions for the core messaging system.
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
use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Classes\Permission as Permission;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Sanitize as Sanitize;
use TempusProjectCore\Core\Template as Template;

class Message extends Controller
{
    private $messages;
    private $usernames;

    public function __construct()
    {
        Debug::log('Model Constructed: '.get_class($this));
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
     * This function is used to install database structures and configuration
     * options needed for this model.
     *
     * @return boolean - The status of the completed install.
     */
    public static function install()
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
        Permission::addPerm('sendMessage', false);
        Permission::savePerms(true);
        return self::$db->getStatus();
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
        self::$template->set('PID', $id);
        // need to move mark read to somewhere more logical
        
        if ($markRead == true) {
            foreach ($messageData as $instance) {
                $this->markRead($instance->ID);
            }
        }
        return $this->processMessage($messageData);
    }

    public function getInbox($limit = null)
    {
        if (!empty($limit)) {
            $limit = [0, $limit];
        }
        $messageData = self::$db->getPaginated('messages', ['userTo', '=', self::$activeUser->ID, "AND", 'parent', '=', 0, "AND", 'recieverDeleted', '=', 0], 'ID', 'DESC', $limit);
        if ($messageData->count() == 0) {
            Debug::info('No messages found');
            return false;
        }
        return $this->processMessage($messageData->results());
    }

    /**
     * This function calls the view for the message outbox.
     *
     * @return null
     */
    public function getOutbox($limit = null)
    {
        if (!empty($limit)) {
            $messageData = self::$db->get('messages', ['userFrom', '=', self::$activeUser->ID, "AND", 'parent', '=', 0, "AND", 'senderDeleted', '=', 0], 'ID', 'DESC', [0, $limit]);
        } else {
            $messageData = self::$db->getPaginated('messages', ['userFrom', '=', self::$activeUser->ID, "AND", 'parent', '=', 0, "AND", 'senderDeleted', '=', 0], 'ID', 'DESC');
        }
        if ($messageData->count() == 0) {
            Debug::info('No messages found');
            return false;
        }
        return $this->processMessage($messageData->results());
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
    private function processMessage($messageArray)
    {
        foreach ($messageArray as &$message) {
            $short = explode(" ", Sanitize::contentShort($message->message));
            $summary = implode(" ", array_splice($short, 0, 25));
            if (count($short, 1) >= 25) {
                $message->summary = $summary . '...';
            } else {
                $message->summary = $summary;
            }
            if ($message->isRead == 0) {
                $message->unreadBadge = self::$template->standardView('message.unread.badge');
            } else {
                $message->unreadBadge = '';
            }
            $message->fromAvatar = self::$user->getAvatar($message->userFrom);
            $message->userTo = self::$user->getUsername($message->userTo);
            $message->userFrom = self::$user->getUsername($message->userFrom);
        }
        return $messageArray;
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
        Debug::error('check');
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
        if (!self::$db->update('messages', $messageData->ID, ['lastReply' => time(),'isRead' => 0])) {
            new CustomException('messagesReplyUpdate');
            return false;
        }
        $fields = ['userTo' => $recipient,
                   'userFrom' => self::$activeUser->ID,
                   'message' => $message,
                   'subject' => 're: ' . $messageData->subject,
                   'sent' => time(),
                   'parent' => $messageData->ID,
                   'lastReply' => time()];
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
