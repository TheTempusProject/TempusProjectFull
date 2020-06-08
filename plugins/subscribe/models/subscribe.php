<?php
/**
 * models/subscribe.php
 *
 * This class is used for the manipulation of the subscribers database table.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Code;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Core\DatabaseModel;

class Subscribe extends DatabaseModel
{
    public static $tableName = "subscribers";
    protected static $log;

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
     * This function is used to install database structures needed for this model.
     *
     * @return boolean - The status of the completed install
     */
    public static function installDB()
    {
        self::$db->newTable(self::$tableName);
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
    public function add($email)
    {
        if (!Check::email($email)) {
            return false;
        }
        $alreadyExists = self::$db->get(self::$tableName, ['email', '=', $email]);
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
        self::$db->insert(self::$tableName, $fields);
        return true;
    }

    /**
     * Removes an email from the subscribers database.
     *
     * @param string $email - The email you are trying to remove.
     * @param string $code - The confirmation code to unsubscribe.
     *
     * @return boolean
     */
    public function unsubscribe($email, $code)
    {
        if (!Check::email($email)) {
            return false;
        }
        $user = self::$db->get(self::$tableName, ['email', '=', $email, 'AND', 'confirmationCode', '=', $code]);
        if (!$user->count()) {
            Debug::info(__METHOD__ . ' - Cannot find subscriber with that email and code');
            return false;
        }
        self::$db->delete(self::$tableName, ['ID', '=', $user->first()->ID]);
        return true;
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

        $data = self::$db->get(self::$tableName, ["email", '=', $email]);
        if (!$data->count()) {
            Debug::info(__METHOD__ . ' - Email not found');
            return false;
        }

        return (object) $data->first();
    }
}
