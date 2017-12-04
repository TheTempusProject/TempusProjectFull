<?php
/**
 * Models/bugreport.php
 *
 * This class is used for the manipulation of the bugreports database table.
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
use TempusProjectCore\Classes\Permission as Permission;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\CustomException as CustomException;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Core\Updater as Updater;

class Bugreport extends Controller
{
    private static $enabled = null;
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
        self::$db->newTable('bugreports');
        self::$db->addfield('userID', 'int', '11');
        self::$db->addfield('time', 'int', '10');
        self::$db->addfield('repeat', 'varchar', '5');
        self::$db->addfield('ourl', 'varchar', '256');
        self::$db->addfield('url', 'varchar', '256');
        self::$db->addfield('ip', 'varchar', '15');
        self::$db->addfield('description', 'text', '');
        self::$db->createTable();
        Permission::addPerm('bugreport', false);
        Permission::savePerms(true);
        Config::addConfigCategory('bugreports');
        Config::addConfig('bugreports', 'enabled', true);
        Config::addConfig('bugreports', 'email', true);
        Config::addConfig('bugreports', 'emailCopy', true);
        Config::addConfig('bugreports', 'emailTemplate', true);
        Config::saveConfig();
        return self::$db->getStatus();
    }

    private static function enabled()
    {
        if (empty(self::$enabled)) {
            self::$enabled = (DB::enabled() && Config::get('bugreports/enabled') == true);
        }
        return self::$enabled;
    }

    /**
     * Select a bug report from the logs table.
     *
     * @param  int $ID - The bug report ID.
     *
     * @return array
     */
    public function get($ID)
    {
        if (!Check::id($ID)) {
            return false;
        }
        $data = self::$db->get('bugreports', ['ID', '=', $ID]);
        if ($data->count() == 0) {
            Debug::info('Bug report not found.');
            return false;
        }
        return $this->parse($data->first());
    }

    /**
     * This function parses the bug reports description and
     * separates it into separate keys in the array.
     *
     * @param  array $data - The data being parsed.
     *
     * @return array
     */
    private function parse($data)
    {
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
     * Retrieves a list of all bug reports
     *
     * @param  string $filter WIP
     *
     * @return bool|array
     */
    public function listReports($filter = null)
    {
        $data = self::$db->getPaginated('bugreports', '*');
        if ($data->count() == 0) {
            Debug::info('No bug reports found.');
            return false;
        }

        return (object) $this->parse($data->results());
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
    /**
     * Function to clear logs of a defined type.
     *
     * @param  string $data - The log type to be cleared
     *
     * @return bool
     *
     * @todo  this is probably dumb
     */
    public function clear()
    {
        self::$db->delete('bugreports', ['ID', '>=', '0']);
        self::$log->admin("Cleared Bug Reports");
        Debug::info("Bug Reports Cleared");
        return true;
    }
    
    /**
     * Function to delete the specified log.
     *
     * @param  int|array $data the log ID or array of ID's to be deleted
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
            self::$db->delete('bugreports', ['ID', '=', $instance]);
            self::$log->admin("Deleted Bug Report: $instance");
            Debug::info("Report deleted: $instance");
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
}
