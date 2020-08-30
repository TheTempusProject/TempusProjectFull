<?php
/**
 * Models/bugreport.php
 *
 * This class is used for the manipulation of the bugreports database table.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Permission;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\CustomException;
use TempusProjectCore\Classes\DB;
use TempusProjectCore\Core\DatabaseModel;

class Bugreport extends DatabaseModel
{
    public static $tableName = "bugreports";
    protected static $user;
    protected static $enabled = null;

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
    public function requiredModels()
    {
        $required = [
            'log',
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
            'installConfigs' => true
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
        self::$db->newTable(self::$tableName);
        self::$db->addfield('userID', 'int', '11');
        self::$db->addfield('time', 'int', '10');
        self::$db->addfield('repeat', 'varchar', '5');
        self::$db->addfield('ourl', 'varchar', '256');
        self::$db->addfield('url', 'varchar', '256');
        self::$db->addfield('ip', 'varchar', '15');
        self::$db->addfield('description', 'text', '');
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
        Permission::addPerm('bugreport', false);
        return Permission::savePerms(true);
    }

    /**
     * Install configuration options needed for the model.
     *
     * @return bool - If the configurations were added without error
     */
    public static function installConfigs()
    {
        Config::addConfigCategory(self::$configName);
        Config::addConfig(self::$configName, 'enabled', true);
        Config::addConfig(self::$configName, 'email', true);
        Config::addConfig(self::$configName, 'emailCopy', true);
        Config::addConfig(self::$configName, 'emailTemplate', true);
        return Config::saveConfig();
    }

    /**
     * This function parses the bug reports description and
     * separates it into separate keys in the array.
     *
     * @param  array $data - The data being parsed.
     *
     * @return array
     */
    private function filter($data)
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        foreach ($data as $instance) {
            if (!is_object($instance)) {
                $instance = $data;
                $end = true;
            }
            $instance->submittedBy = self::$user->getUsername($instance->userID);
            $out[] = $instance;
            if (!empty($end)) {
                break;
            }
        }
        return $out;
    }

    /**
     * Logs a Bug Report form.
     *
     * @param  int $ID           the user ID submitting the form
     * @param  string $url          the url
     * @param  string $o_url        the original url
     * @param  int $repeat       is repeatable?
     * @param  string $description_ description of the event.
     *
     * @return null
     */
    public static function create($ID, $url, $oUrl, $repeat, $description)
    {
        if (!Check::id($ID)) {
            return false;
        }
        if (!self::enabled()) {
            Debug::info('Bug Report Logging is disabled in the config.');

            return false;
        }
        $fields = [
            'userID' => $ID,
            'time' => time(),
            'repeat' => $repeat,
            'ourl' => $oUrl,
            'url' => $url,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'description' => $description,
        ];
        if (!self::$db->insert('bugreports', $fields)) {
            new CustomException('bugreports');

            return false;
        }
        return true;
    }
}
