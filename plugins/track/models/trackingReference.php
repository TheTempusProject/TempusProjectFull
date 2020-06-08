<?php
/**
 * track/models/trackingReference.php
 *
 * This class is used to provide a reference table for the link tracking system
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Core\DatabaseModel;

class TrackingReference extends DatabaseModel
{
    public static $tableName = "trackingReference";

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
        self::$db->addfield('createdBy', 'int', '10');
        self::$db->addfield('createdAt', 'int', '10');
        self::$db->addfield('linkType', 'varchar', '32');
        self::$db->addfield('trackingHash', 'varchar', '256');
        self::$db->createTable();
        
        return self::$db->getStatus();
    }
}
