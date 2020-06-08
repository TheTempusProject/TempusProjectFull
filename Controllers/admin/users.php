<?php
/**
 * Controllers/Admin/Users.php
 *
 * This is the admin users controller.
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

use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Image;
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Users extends AdminController
{
    public function __construct($data = null)
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Users';
        if (Input::post('submit') == 'delete') {
            $sub = 'delete';
        }
        if (Input::post('submit') == 'edit') {
            $sub = 'edit';
        }
        self::$user = $this->model('user');
        self::$group = $this->model('group');
    }
    public function index($data = null)
    {
        $this->view('admin.userList', self::$user->userList());
        exit();
    }
    public function delete($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (Input::exists('submit')) {
            $data = Input::post('U_');
        }
        if (self::$user->delete((array) $data)) {
            Issue::success('User Deleted');
        } else {
            Issue::error('There was an error deleting that user');
        }
        $this->index();
    }
    public function viewUser($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (!empty($data)) {
            $userData = self::$user->get($data);
            if ($userData !== false) {
                $this->view('admin.userView', $userData);
                exit();
            }
            Issue::error('User not found.');
        }
        $this->index();
    }
    public function edit($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        if (empty($data) && Input::exists('U_')) {
            $data = Input::post('U_');
        }
        if (!Check::id($data)) {
            Issue::error('invalid user.');
            exit();
        }
        $currentUser = self::$user->get($data);
        if (Input::exists('submit') && Input::post('submit') != 'edit') {
            if (Input::exists('avatar')) {
                if (Image::upload('avatar', self::$activeUser->username)) {
                    $avatar = 'Uploads/Images/' . self::$activeUser->username . '/' . Image::last();
                } else {
                    $avatar = $currentUser->avatar;
                }
            } else {
                $avatar = $currentUser->avatar;
            }

            $passed = self::$user->updatePrefs([
                'avatar'      => $avatar,
                'timezone'    => Input::post('timezone'),
                'gender'    => Input::post('gender'),
                'dateFormat' => Input::post('dateFormat'),
                'timeFormat' => Input::post('timeFormat'),
                'pageLimit'  => Input::post('pageLimit'),
            ], $currentUser->ID);
            self::$user->update(['username' => Input::post('username'), 'userGroup' => Input::post('groupSelect')], $currentUser->ID);
            if ($passed) {
                Issue::success('User Updated.');
            } else {
                Issue::warning('There was an error with your request, please try again.');
            }
            self::$template->selectOption(Input::post('groupSelect'));
            self::$template->selectOption(Input::post('timezone'));
            self::$template->selectOption(Input::post('dateFormat'));
            self::$template->selectOption(Input::post('timeFormat'));
            self::$template->selectOption(Input::post('pageLimit'));
            self::$template->selectOption(Input::post('gender'));
        } else {
            self::$template->selectOption(($currentUser->userGroup));
            self::$template->selectOption(($currentUser->timezone));
            self::$template->selectOption(($currentUser->dateFormat));
            self::$template->selectOption(($currentUser->timeFormat));
            self::$template->selectOption(($currentUser->pageLimit));
            self::$template->selectOption(($currentUser->gender));
        }
        if (empty($avatar)) {
            $avatar = $currentUser->avatar;
        }
        self::$template->set('AVATAR_SETTINGS', $avatar);
        self::$template->set('TIMEZONELIST', self::$template->standardView('timezoneDropdown'));
        $select = self::$template->standardView('admin.groupSelect', self::$group->listGroups());
        self::$template->set('groupSelect', $select);
        $this->view('admin.userEdit', $currentUser);
        exit();
    }
}
