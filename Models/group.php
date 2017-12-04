<?php
/**
 * Models/group.php
 *
 * This class is used for the manipulation of the groups database table.
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
use TempusProjectCore\Functions\Docroot as Docroot;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Classes\Session as Session;
use TempusProjectCore\Classes\Cookie as Cookie;
use TempusProjectCore\Classes\Log as Log;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Email as Email;
use TempusProjectCore\Core\Installer as Installer;

class Group extends Controller
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
        Config::addConfigCategory('group');
        Config::addConfig('group', 'defaultGroup', 5);
        Config::saveConfig();
        self::$db->newTable('groups');
        self::$db->addfield('name', 'varchar', '32');
        self::$db->addfield('permissions', 'text', '');
        self::$db->createTable();
        $fields = [
            'name' => 'Admin',
            'permissions' =>'{"uploadImages":true,"sendMessages":true,"pageLimit":100,"adminAccess":true,"modAccess":true,"memberAccess":true,"bugReport":true,"feedback":true}'
            ];
        self::$db->insert('groups', $fields);
        $fields = [
            'name' => 'Moderator',
            'permissions' =>'{"uploadImages":true,"sendMessages":true,"pageLimit":75,"adminAccess":false,"modAccess":true,"memberAccess":true,"bugReport":true,"feedback":true}'
            ];
        self::$db->insert('groups', $fields);
        $fields = [
            'name' => 'Member',
            'permissions' =>'{"uploadImages":true,"sendMessages":true,"pageLimit":50,"adminAccess":false,"modAccess":false,"memberAccess":true,"bugReport":true,"feedback":true}'
            ];
        self::$db->insert('groups', $fields);
        $fields = [
            'name' => 'User',
            'permissions' =>'{"uploadImages":true,"sendMessages":true,"pageLimit":25,"adminAccess":false,"modAccess":false,"memberAccess":false,"bugReport":true,"feedback":true}'
            ];
        self::$db->insert('groups', $fields);
        $fields = [
            'name' => 'Guest',
            'permissions' =>'{"uploadImages":false,"sendMessages":false,"pageLimit":10,"adminAccess":false,"modAccess":false,"memberAccess":false,"bugReport":false,"feedback":true}'
            ];
        self::$db->insert('groups', $fields);
        return self::$db->getStatus();
    }

    public static function isEmpty($data)
    {
        if (!Check::ID($data)) {
            return false;
        }
        $userData = self::$db->get('users', ['userGroup', '=', $data]);
        if (!$userData->count()) {
            return true;
        }
        return false;
    }
    /**
     * Function to delete the specified group.
     *
     * @param  int|array $ID the log ID or array of ID's to be deleted
     *
     * @return bool
     */
    public function deleteGroup($data)
    {
        foreach ($data as $instance) {
            if (!is_array($data)) {
                $instance = $data;
                $end = true;
            }
            if (!Check::id($instance)) {
                $error = true;
            }
            if (self::$group->countMembers($instance) !== 0) {
                Debug::info('Group is not empty.');
                return false;
            }
            if ($instance == Config::get('group/defaultGroup')) {
                Debug::info('Cannot delete the default group.');
                return false;
            }
            self::$db->delete('groups', ['ID', '=', $instance]);
            self::$log->admin("Deleted group: $instance");
            Debug::info("Group deleted: $instance");
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

    public static function formToJson($pageLimit)
    {
        if (!Check::id($pageLimit)) {
            Debug::warn('Invalid number supplied for page limit.');
            return false;
        }
        $form = [
            'uploadImages' => 'uploadImages',
            'sendMessages' => 'sendMessages',
            'feedback' => 'feedback',
            'bugreport' => 'bugReport',
            'member' => 'memberAccess',
            'modCP' => 'modAccess',
            'adminCP' => 'adminAccess'
        ];
        foreach ($form as $key => $value) {
            if (Input::exists($key)) {
                $data[$value] = true;
            } else {
                $data[$value] = false;
            }
        }
        $data['pageLimit'] = $pageLimit;
        $out = json_encode($data);
        return $out;
    }

    public static function create($name, $permissions)
    {
        if (!Check::dataTitle($name)) {
            Debug::info("modelGroup: illegal group name.");
            
            return false;
        }
        $fields = [
            'name' => $name,
            'permissions' => $permissions,
            ];
        if (self::$db->insert('groups', $fields)) {
            self::$log->admin("Created Group: " . $name);
            return true;
        }
        return false;
    }

    public static function update($id, $name, $permissions)
    {
        if (!Check::id($id)) {
            return false;
        }
        if (!Check::dataTitle($name)) {
            Debug::info("modelGroup: illegal group name.");
            
            return false;
        }
        $fields = [
            'name' => $name,
            'permissions' => $permissions,
            ];
        if (self::$db->update('groups', $id, $fields)) {
            self::$log->admin("updated Group: $id");
            return true;
        }
        return false;
    }

    public static function getPermissions($data)
    {
        if (!is_object($data)) {
            $docLocation = Docroot::getLocation('appPermissions');
            if ($docLocation->error) {
                $docLocation = Docroot::getLocation('appPermissionsDefault');
                if ($docLocation->error) {
                    $docLocation = Docroot::getLocation('permissionsDefault');
                }
            }
            $json = json_decode(file_get_contents($docLocation->fullPath), true);
        } else {
            $json = json_decode($data->permissions, true);
        }
        foreach ($json as $key => $value) {
            $name = $key . '_text';
            $name2 = $key . '_string';
            if ($value == true && is_bool($value)) {
                $json[$name] = 'yes';
                $json[$name2] = 'true';
            } else {
                $json[$name] = 'no';
                $json[$name2] = 'false';
            }
        }
        $json['userCount'] = self::countMembers($data->ID);
        $groupData = (object) array_merge($json, (array) $data);
        return $groupData;
    }
    // @todo this should return the whole object, not just permissions
    public static function findByName($name)
    {
        if (!Check::dataString($name)) {
            return false;
        }
        $groupData = self::$db->get('groups', ['name', '=', $name]);
        if (!$groupData->count()) {
            Debug::warn('Could not find a group named: ' . $name);
            return false;
        }
        return self::getPermissions($groupData->first());
    }

    public static function findById($id)
    {
        if (!Check::id($id)) {
            return false;
        }
        $groupData = self::$db->get('groups', ['ID', '=', $id]);
        if (!$groupData->count()) {
            Debug::warn('Could not find a group with ID: ' . $id);
            return false;
        }
        return self::getPermissions($groupData->first());
    }

    public static function listGroups()
    {
        $db = self::$db->getPaginated('groups', ['ID', '>=', '0']);
        if (!$db->count()) {
            Debug::warn('Could not find any groups');
            return false;
        }
        $x = 0;
        $groups = $db->results();
        foreach ($groups as &$group) {
            $group->userCount = self::countMembers($group->ID);
        }
        return $groups;
    }

    public static function listMembers($id)
    {
        if (!Check::id($id)) {
            return false;
        }
        $group = self::findById($id);
        if ($group === false) {
            return false;
        }
        $members = self::$db->getPaginated('users', ['userGroup', '=', $id]);
        if (!$members->count()) {
            Debug::info('list members: Could not find anyone in group: ' . $id);
            return false;
        }
        $out = $members->results();
        return $out;
    }

    /**
     *
     *
     * @param  integer $id - The group ID to count the members of
     *
     * @return boolean|integer
     */
    public static function countMembers($id)
    {
        if (!Check::id($id)) {
            return false;
        }
        $userData = self::$db->get('users', ['userGroup', '=', $id]);
        if (!$userData->count()) {
            Debug::info('count members: Could not find anyone in group: ' . $id);
            return 0;
        }
        return $userData->count();
    }
}
