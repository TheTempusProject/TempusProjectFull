<?php
/**
 * track/models/track.php
 *
 * This class is used to provide a link tracking system
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Core\DatabaseModel;

class Track extends DatabaseModel
{
    public static $tableName = "tracking_links";

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
    public static function requiredModels()
    {
        $required = [
            'log',
            'trackingReference'
        ];
        return $required;
    }

    /**
     * This function is used to install database structures needed for this model.
     *
     * @return boolean - The status of the completed install
     */
    public static function installDB()
    {
        self::$db->newTable(self::$tableName);
        self::$db->addfield('referer', 'varchar', '1024');
        self::$db->addfield('trackingHash', 'varchar', '256');
        self::$db->addfield('time', 'int', '10');
        self::$db->addfield('data', 'text', '');
        self::$db->createTable();

        return self::$db->getStatus();
    }
}
