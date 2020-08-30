<?php
/**
 * models/defaultModel.php
 *
 * This class is an default/testing model.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Classes\Debug as Debug;
use TempusProjectCore\Classes\Preference as Preference;
use TempusProjectCore\Classes\Permission as Permission;
use TempusProjectCore\Classes\Config as Config;
use TempusProjectCore\Core\Controller as Controller;

class DefaultModel extends Controller
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
        self::$db->newTable('testTable');
        self::$db->addfield('name', 'varchar', '80');
        self::$db->createTable();
        Preference::addPref('testingPreference', true);
        Preference::savePrefs(true);
        Permission::addPerm('testPermission', false);
        Permission::savePerms(true);
        Config::addConfigCategory('testing');
        Config::addConfig('testing', 'testingEnabled', true);
        Config::saveConfig();
        return self::$db->getStatus();
    }
}
