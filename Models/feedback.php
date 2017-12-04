<?php
/**
 * Models/feedback.php
 *
 * This class is used for the manipulation of the feedback database table.
 *
 * @todo  make this send a confirmation email
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
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Classes\Permission as Permission;
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\DB as DB;
use TempusProjectCore\Core\Installer as Installer;

class Feedback extends Controller
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
        self::$db->newTable('feedback');
        self::$db->addfield('name', 'varchar', '32');
        self::$db->addfield('time', 'int', '10');
        self::$db->addfield('email', 'varchar', '75');
        self::$db->addfield('ip', 'varchar', '15');
        self::$db->addfield('feedback', 'text', '');
        self::$db->createTable();
        Config::addConfigCategory('feedback');
        Config::addConfig('feedback', 'enabled', true);
        Config::addConfig('feedback', 'sendEmail', false);
        Config::addConfig('feedback', 'emailTemplate', 'default');
        Config::addConfig('feedback', 'emailVersion', 'feedbackResponse');
        Config::saveConfig();
        Permission::addPerm('feedback', false);
        Permission::savePerms(true);
        return self::$db->getStatus();
    }
    private static function enabled()
    {
        if (empty(self::$enabled)) {
            self::$enabled = (DB::enabled() && Config::get('feedback/enabled') == true);
        }
        return self::$enabled;
    }
    /**
     * Select feedback from the logs table.
     *
     * @param  int $id - The feedback id.
     *
     * @return array
     */
    public function get($id)
    {
        if (!Check::id($id)) {
            return false;
        }
        $data = self::$db->get('feedback', ['ID', '=', $id]);
        if (!$data->count()) {
            Debug::info('Feedback::getList - feedback not found.');
            return false;
        }
        return $data->first();
    }

    /**
     * Retrieves a list of all feedback.
     *
     * @param  string $filter WIP
     *
     * @return bool|array
     *
     * @todo  add filters
     */
    public function getList()
    {
        $data = self::$db->getPaginated('feedback', '*');
        if (!$data->count()) {
            Debug::info('Feedback::getList - no feedback found in db.');
            return false;
        }
        return $data->results();
    }

    /**
     * Saves a feedback form to the db.
     *
     * @param  string $name     -the name on the form
     * @param  string $email    -the email provided
     * @param  string $feedback -contents of the feedback form.
     *
     * @return bool
     */
    public static function create($name, $email, $feedback)
    {
        if (!self::enabled()) {
            Debug::warn('Feedback is disabled in the config.');
            return false;
        }
        $fields = [
            'name' => $name,
            'email' => $email,
            'feedback' => $feedback,
            'time' => time(),
            'ip' => $_SERVER['REMOTE_ADDR'],
        ];
        if (!self::$db->insert('feedback', $fields)) {
            Debug::info('Feedback::create - failed to insert to db');
            return false;
        }
        return true;
    }

    /**
     * Function to clear feedback from the DB.
     *
     * @todo    is there  a way i could check for success here
     *          i'm pretty sure this is just a bad idea?
     *
     * @return bool
     */
    public function clear()
    {
        self::$db->delete('feedback', ['ID', '>=', '0']);
        self::$log->admin('Cleared Feedback');
        Debug::info("Feedback Cleared");
        return true;
    }

    /**
     * Function to delete the specified feedback.
     *
     * @param  int|array $data - The ID or ID's to be deleted.
     *
     * @return boolean
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
            self::$db->delete('feedback', ['ID', '=', $instance]);
            self::$log->admin("Deleted Feedback: $instance");
            Debug::info("Feedback Deleted: $instance");
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
