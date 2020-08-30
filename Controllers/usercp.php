<?php
/**
 * Controllers/usercp.php
 *
 * This is the userCP controller.
 *
 * @version 3.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Functions\Routes;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Image;
use TempusProjectCore\Classes\Email;
use TempusProjectCore\Classes\Hash;
use TempusProjectCore\Classes\Code;
use TempusProjectCore\Classes\Check;

class Usercp extends Controller
{
    protected static $session;
    protected static $user;
    protected static $message;

    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$template->activePageSelect('navigation.usercp');
        self::$template->noIndex();
        if (!self::$isLoggedIn) {
            Issue::notice('You must be logged in to view this page!');
            exit();
        }
        self::$session = $this->model('sessions');
        self::$user = $this->model('user');
        self::$message = $this->model('message');
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
        self::$title = 'User Control Panel';
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $this->view('user', self::$activeUser);
        exit();
    }

    public function settings()
    {
        self::$title = 'Preferences';
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        self::$template->set('TIMEZONELIST', self::$template->standardView('timezoneDropdown'));
        $a = Input::exists('submit');
        $value = $a ? Input::post('updates') : self::$activePrefs->email;
        self::$template->selectRadio('updates', $value);
        $value = $a ? Input::post('newsletter') : self::$activePrefs->newsletter;
        self::$template->selectRadio('newsletter', $value);
        self::$template->selectOption(($a ? Input::post('timezone') : self::$activePrefs->timezone));
        self::$template->selectOption(($a ? Input::post('dateFormat') : self::$activePrefs->dateFormat));
        self::$template->selectOption(($a ? Input::post('timeFormat') : self::$activePrefs->timeFormat));
        self::$template->selectOption(($a ? Input::post('pageLimit') : self::$activePrefs->pageLimit));
        self::$template->selectOption(($a ? Input::post('gender') : self::$activePrefs->gender));
        self::$template->set('AVATAR_SETTINGS', self::$activePrefs->avatar);
        if ($a) {
            if (!Check::form('userPrefs')) {
                Issue::error('There was an error with your request.', Check::userErrors());
                $this->view('usercpSettings', self::$activeUser);
                exit();
            }
            if (Input::exists('avatar') && Image::upload('avatar', self::$activeUser->username)) {
                $avatar = 'Uploads/Images/' . self::$activeUser->username . '/' . Image::last();
            }
            $fields = [
                "timezone" =>    Input::post('timezone'),
                "dateFormat" => Input::post('dateFormat'),
                "timeFormat" => Input::post('timeFormat'),
                "pageLimit" => Input::post('pageLimit'),
                "email" => Input::post('updates'),
                "gender" => Input::post('gender'),
                "newsletter" =>  Input::post('newsletter'),
            ];
            if (isset($avatar)) {
                $fields = array_merge($fields, ['avatar' => $avatar]);
            }
            self::$user->updatePrefs($fields, self::$activeUser->ID);
        }
        if (!isset($avatar)) {
            $avatar = self::$activePrefs->avatar;
        }
        self::$template->set('AVATAR_SETTINGS', $avatar);
        $this->view('usercpSettings', self::$activeUser);
        exit();
    }

    public function email()
    {
        self::$title = 'Email Settings';
        Debug::log("Controller initiated: " . __METHOD__ . ".");

        if (self::$activeUser->confirmed != '1') {
            Issue::notice('You need to confirm your email address before you can make modifications. If you would like to resend that confirmation link, please <a href="{BASE}register/resend">click here</a>');
            exit();
        }

        if (!Input::exists()) {
            $this->view('usercpEmailChange');
            exit();
        }

        if (!Check::form('changeEmail')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('usercpEmailChange');
            exit();
        }

        $code = Code::genConfirmation();
        self::$user->update([
            'confirmed' => 0,
            'email' => Input::post('email'),
            'confirmationCode' => $code,
            ], self::$activeUser->ID);
        Email::send(self::$activeUser->email, 'emailChangeNotice', $code, ['template' => true]);
        Email::send(Input::post('email'), 'emailChange', $code, ['template' => true]);
        Issue::notice('Email has been changed, please check your email to confirm it.');
    }

    public function password()
    {
        self::$title = 'Password Settings';
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (!Input::exists()) {
            $this->view('passwordChange');
            exit();
        }
        if (!Hash::check(Input::post('curpass'), self::$activeUser->password)) {
            Issue::error('Current password was incorrect.');
            $this->view('passwordChange');
            exit();
        }
        if (!Check::form('changePassword')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('passwordChange');
            exit();
        }
        self::$user->update(['password' => Hash::make(Input::post('password'))], self::$activeUser->ID);
        Email::send(self::$activeUser->email, 'passwordChange', null, ['template' => true]);
        Issue::notice('Your Password has been changed!');
    }

    public function messages($action = null, $data = null)
    {
        self::$title = 'Messages';
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $action = strtolower($action);
        switch ($action) {
            case 'viewmessage':
                self::$title = self::$message->messageTitle($data);
                $this->view('message.message', self::$message->getThread($data, true));
                exit();

            case 'reply':
                if (Input::exists('messageID')) {
                    $data = Input::post('messageID');
                }
                if (!Check::id($data)) {
                    Issue::error('There was an error with your request.');
                    break;
                }
                self::$title .= ' - Reply to: ' . self::$message->messageTitle($data);
                if (!Input::exists('message')) {
                    self::$template->set('messageID', $data);
                    $this->view('message.reply');
                    exit();
                }
                if (!Check::form('replyMessage')) {
                    Issue::error('There was an problem sending your message.', Check::userErrors());
                    self::$template->set('messageID', $data);
                    $this->view('message.reply');
                    exit();
                }
                if (!self::$message->newMessageReply($data, Input::post('message'))) {
                    Issue::error('There was an error with your request.');
                    break;
                }
                Issue::success('Reply Sent.');
                break;

            case 'newmessage':
                self::$title .= ' - New Message';
                if (Input::get('prepopuser')) {
                    $data = Input::get('prepopuser');
                }
                if (!empty($data) && Check::username($data)) {
                    self::$template->set('prepopuser', $data);
                } else {
                    self::$template->set('prepopuser', '');
                }
                if (!Input::exists('submit')) {
                    $this->view('message.new');
                    exit();
                }
                if (!Check::form('newMessage')) {
                    Issue::error('There was an problem sending your message.', Check::userErrors());
                    $this->view('message.new');
                    exit();
                }
                if (self::$message->newThread(Input::post('toUser'), Input::post('subject'), Input::post('message'))) {
                    Issue::success('Message Sent.');
                } else {
                    Issue::notice('There was an problem sending your message.');
                }
                break;

            case 'markread':
                self::$message->markRead($data);
                break;

            case 'delete':
                if (Input::exists('T_')) {
                    self::$message->deleteMessage(Input::post('T_'));
                }
                if (Input::exists('F_')) {
                    self::$message->deleteMessage(Input::post('F_'));
                }
                if (Input::exists('ID')) {
                    self::$message->deleteMessage([Input::get('ID')]);
                }
                if (!empty($data)) {
                    self::$message->deleteMessage([$data]);
                }
                break;
        }
        $this->view('message.inbox', self::$message->getInbox());
        $this->view('message.outbox', self::$message->getOutbox());
    }
}
