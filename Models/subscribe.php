<?php
/**
 * Models/subscribe.php
 *
 * This class is used for the manipulation of the subscribers database table.
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
use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Core\Installer as Installer;

class Subscribe extends Controller
{
    public function __construct()
    {
        Debug::log('Model Constructed: ' . get_class($this));
    }

    /**
     * This function is used to install database structures and configuration
     * options needed for this model.
     *
     * @return boolean - The status of the completed install.
     */
    public static function install()
    {
        self::$db->newTable('subscribers');
        self::$db->addfield('confirmed', 'int', '1');
        self::$db->addfield('subscribed', 'int', '10');
        self::$db->addfield('confirmationCode', 'varchar', '80');
        self::$db->addfield('email', 'varchar', '75');
        self::$db->createTable();
        return self::$db->getStatus();
    }

    /**
     * Adds an email to the subscribers database.
     *
     * @param string $email - the email you are trying to add.
     *
     * @return bool
     */
    public static function add($email)
    {
        if (!Check::email($email)) {
            return false;
        }
        $alreadyExists = self::$db->get('subscribers', ['email', '=', $email]);
        if ($alreadyExists->count()) {
            Debug::info('email already subscribed.');

            return false;
        }
        $fields = [
            'email'             => $email,
            'confirmationCode' => Code::genConfirmation(),
            'confirmed'         => 0,
            'subscribed'         => time(),
        ];
        self::$db->insert('subscribers', $fields);
        return true;
    }

    /**
     * Removes an email from the subscribers database.
     *
     * @param string $data - The email you are trying to remove.
     * @param string $code - The confirmation code to unsubscribe.
     *
     * @return boolean
     */
    public static function unsubscribe($email, $code)
    {
        if (!Check::email($email)) {
            return false;
        }
        $user = self::$db->get('subscribers', ['email', '=', $email, 'AND', 'confirmationCode', '=', $code]);
        if (!$user->count()) {
            Debug::info('subscribe::unsubscribe - Cannot find subscriber with that email and code');
            return false;
        }
        self::$db->delete('subscribers', ['ID', '=', $user->first()->ID]);
        return true;
    }

    /**
     * Removes an email from the subscribers table.
     *
     * @param string $id - The email you are trying to remove.
     *
     * @return bool
     */
    public static function remove($data)
    {
        foreach ($data as $instance) {
            if (!is_array($data)) {
                $instance = $data;
                $end = true;
            }
            if (!Check::id($instance)) {
                $error = true;
            }
            self::$db->delete('subscribers', ['ID', '=', $instance]);
            self::$log->admin("Deleted subscriber: $instance");
            Debug::info("subscriber Deleted: $instance");
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
     * Compiles a list of all subscribers, allowing for filtering the list.
     *
     * @param  array $filter - A filter to be applied to the subscriber list.
     *
     * @return bool|object - Depending on success.
     */
    public function listSubscribers($filter = null)
    {
        $data = self::$db->getPaginated('subscribers', "*");
        if (!$data->count()) {
            Debug::info('subscribe::listSubscribers - No subscribers found');
            return false;
        }
        return (object) $data->results();
    }

    /**
     * Returns a subscriber object for the provided email address.
     *
     * @param  string $email - An email address to look for.
     *
     * @return bool|object - Depending on success.
     */
    public function get($email)
    {
        if (!Check::email($email)) {
            return false;
        }

        $data = self::$db->get('subscribers', ["email", '=', $email]);
        if (!$data->count()) {
            Debug::info('subscribe::listSubscribers - Email not found');
            return false;
        }

        return (object) $data->first();
    }
}
