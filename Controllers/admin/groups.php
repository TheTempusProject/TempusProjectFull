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
use TempusProjectCore\Classes\Check;
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Groups extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Groups';
        self::$group = $this->model('group');
    }
    public function index($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $this->view('admin.groupList', self::$group->listGroups());
        exit();
    }
    public function viewGroup($data = null)
    {
        Debug::log('Controller initiated: ' . __METHOD__ . '.');
        $groupData = self::$group->findById($data);
        if ($groupData !== false) {
            $this->view('admin.groupView', $groupData);
            exit();
        }
        Issue::error('Group not found');
        $this->index();
    }
    public function listmembers($data = null)
    {
        Debug::log('Controller initiated: ' . __METHOD__ . '.');
        $groupData = self::$group->findById($data);
        if ($groupData !== false) {
            self::$template->set('groupName', $groupData->name);
            $this->view('admin.groupListMembers', self::$group->listMembers($groupData->ID));
            exit();
        }
        Issue::error('Group not found');
        $this->index();
    }
    public function newGroup($data = null)
    {
        Debug::log('Controller initiated: ' . __METHOD__ . '.');
        if (!Input::exists('submit')) {
            $this->view('admin.groupNew');
            exit();
        }
        if (!Check::form('newGroup')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('admin.groupNew');
            exit();
        }
        if (self::$group->create(Input::post('name'), self::$group->formToJson(Input::post('pageLimit')))) {
            Issue::success('Group created');
        } else {
            Issue::error('There was an error creating your group.');
        }
        $this->index();
    }
    public function edit($data = null)
    {
        Debug::log('Controller initiated: ' . __METHOD__ . '.');
        if (!Input::exists('submit')) {
            $groupData = self::$group->findById($data);
            self::$template->selectOption($groupData->pageLimit);
            self::$template->selectRadio('uploadImages', $groupData->uploadImages_string);
            self::$template->selectRadio('sendMessages', $groupData->sendMessages_string);
            self::$template->selectRadio('feedback', $groupData->feedback_string);
            self::$template->selectRadio('bugreport', $groupData->bugReport_string);
            self::$template->selectRadio('member', $groupData->memberAccess_string);
            self::$template->selectRadio('modCP', $groupData->modAccess_string);
            self::$template->selectRadio('adminCP', $groupData->adminAccess_string);
            $this->view('admin.groupEdit', $groupData);
            exit();
        }
        if (!Check::form('newGroup')) {
            Issue::error('There was an error with your request.', Check::userErrors());
            $this->view('admin.groupNew');
            exit();
        }
        if (self::$group->update($data, Input::post('name'), self::$group->formToJson(Input::post('pageLimit')))) {
            Issue::success('Group updated');
        } else {
            Issue::error('There was an error with your request.');
        }
        $this->index();
    }
    public function delete($data = null)
    {
        Debug::log('Controller initiated: ' . __METHOD__ . '.');
        if (Input::exists('G_')) {
            $data = Input::post('G_');
        }
        if (!self::$group->deleteGroup((array) $data)) {
            Issue::error('There was an error with your request.');
        } else {
            Issue::success('Group has been deleted');
        }
        $this->index();
    }
}
