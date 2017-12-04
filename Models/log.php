<?php
/**
 * Models/log.php
 *
 * Model for handling our logging.
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
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Classes\Session as Session;
use TempusProjectCore\Classes\CustomException as CustomException;
use TempusProjectCore\Classes\Cookie as Cookie;
use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Email as Email;
use TempusProjectCore\Core\Installer as Installer;

class Log extends Controller
{
    private static $enabled;
    private $usernames;

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
        self::$db->newTable('logs');
        self::$db->addfield('userID', 'int', '11');
        self::$db->addfield('time', 'int', '10');
        self::$db->addfield('ip', 'varchar', '15');
        self::$db->addfield('source', 'varchar', '64');
        self::$db->addfield('action', 'text', '');
        self::$db->createTable();
        Config::addConfigCategory('logging');
        Config::addConfig('logging', 'admin', true);
        Config::addConfig('logging', 'errors', true);
        Config::addConfig('logging', 'logins', true);
        Config::saveConfig();
        return self::$db->getStatus();
    }

    /**
     * Creates a DB connection.
     */
    private static function enabled($type)
    {
        if (empty(self::$db)) {
            self::$db = DB::getInstance();
        }
        if (empty(self::$enabled)) {
            self::$enabled = DB::enabled();
        }
        if (self::$enabled == false) {
            return false;
        }
        return Config::get('logging/' . $type);
    }

    /**
     * Retrieves a log from the database.
     *
     * @param  int $id - The Log id we are searching for
     *
     * @return bool|object
     */
    public function get($id)
    {
        if (!Check::id($id)) {
            return false;
        }
        $logData = self::$db->get('logs', ['ID', '=', $id]);
        if (!$logData->count()) {
            return false;
        }
        return $this->parseLog($logData->first());
    }
    /**
     * Select feedback from the logs table.
     *
     * @param  int $id - The feedback id.
     *
     * @return array
     */
    public function getError($id)
    {
        if (!Check::id($id)) {
            return false;
        }
        $logData = self::$db->get('logs', ['ID', '=', $id]);
        if (!$logData->count()) {
            return false;
        }
        return $this->parseError($logData->first());
    }

    /**
     * This function parses the error description and
     * separates it into separate keys in the array.
     *
     * @param  array $data - An array of feedback we need to convert.
     *
     * @return array
     */
    private function parseError($data)
    {
        foreach ($data as $instance) {
            if (!is_object($instance)) {
                $instance = $data;
                $end = true;
            }
            $toArray = (array) $instance;
            $data[] = (object) array_merge(json_decode($instance->action, true), $toArray);
            if (!empty($end)) {
                break;
            }
        }
        return $data;
    }
    private function parseLog($data)
    {
        foreach ($data as $instance) {
            if (!is_object($instance)) {
                $instance = $data;
                $end = true;
            }
            $instance->logUser = self::$user->getUsername($instance->userID);
            $out[] = $instance;
            if (!empty($end)) {
                break;
            }
        }
        return $out;
    }
    /**
     * Retrieves a list of all errors.
     *
     * @param  string $filter WIP
     *
     * @return bool|array
     */
    public function errorList($filter = null)
    {
        $logData = self::$db->getPaginated('logs', ['source', '=', 'error']);
        if (!$logData->count()) {
            return false;
        }
        return $this->parseError($logData->results());
    }

    /**
     * Retrieves a list of all logins
     *
     * @param  string $filter WIP
     *
     * @return bool|obj
     */
    public function loginList($filter = null)
    {
        $logData = self::$db->getPaginated('logs', ['source', '=', 'login']);
        if (!$logData->count()) {
            return false;
        }

        return $this->parseLog($logData->results());
    }
    
    /**
     * Retrieves a list of all logins
     *
     * @param  string $filter WIP
     *
     * @return bool|obj
     */
    public function adminList($filter = null)
    {
        $logData = self::$db->getPaginated('logs', ['source', '=', 'admin']);
        if (!$logData->count()) {
            return false;
        }

        return $this->parseLog($logData->results());
    }
    
    /**
     * Function to delete the specified log.
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
            self::$db->delete('logs', ['ID', '=', $instance]);
            $this->admin("Deleted Log: $instance");
            Debug::info("Log deleted: $instance");
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
    
    /**
     * Function to clear logs of a specific type.
     *
     * @param  string $data - The log type to be cleared
     *
     * @return boolean
     *
     * @todo  make into a switch statement
     */
    public function clear($data)
    {
        switch ($data) {
            case 'admin':
                Debug::error('You cannot delete admin logs');
                return false;
            
            case 'login':
                self::$db->delete('logs', ['source', '=', $data]);
                $this->admin("Cleared Logs: $data");
                return true;
            
            case 'error':
                self::$db->delete('logs', ['source', '=', $data]);
                $this->admin("Cleared Logs: $data");
                return true;
            
            default:
                return false;
        }
    }

    /**
     * logs an error to the DB.
     *
     * @param  integer $errorID  - An associated error ID
     * @param  string  $class    - Class where the error occurred
     * @param  string  $function - method in which the error occurred
     * @param  string  $error    - What was the error
     * @param  string  $data     - Any additional info
     */
    public static function error($errorID = 500, $class = null, $function = null, $error = null, $data = null)
    {
        if (!self::enabled('errors')) {
            Debug::info('Error logging is disabled in the config.');
        }
        $data = ['class' => $class, 'function' => $function, 'error' => $error, 'description' => $data];
        $output = json_encode($data);
        $fields = [
            'userID' => $errorID,
            'action' => $output,
            'time' => time(),
            'source' => 'error',
        ];
        if (!self::$db->insert('logs', $fields)) {
            new CustomException('logError', $data);
        }
    }

    /**
     * Logs a login to the DB.
     *
     * @param int    $userID - The User ID being logged in
     * @param string $action - Must be 'pass' or 'fail'
     *
     * @return null
     */
    public static function login($userID, $action = 'fail')
    {
        if (!self::enabled('logins')) {
            Debug::info('Login logging is disabled in the config.');
        }
        $fields = [
            'userID' => $userID,
            'action' => $action,
            'time' => time(),
            'source' => 'login',
            'ip' => $_SERVER['REMOTE_ADDR'],
        ];
        if (!self::$db->insert('logs', $fields)) {
            new CustomException('logLogin');
        }
    }

    /**
     * Logs an admin action to the DB.
     *
     * @param string $action - Must be 'pass' or 'fail'
     */
    public static function admin($action)
    {
        if (!self::enabled('admin')) {
            Debug::info('Admin logging is disabled in the config.');
        }
        $fields = [
            'userID' => self::$activeUser->ID,
            'action' => $action,
            'time' => time(),
            'source' => 'admin',
            'ip' => $_SERVER['REMOTE_ADDR'],
        ];
        if (!self::$db->insert('logs', $fields)) {
            new CustomException('logAdmin');
        }
    }
}
