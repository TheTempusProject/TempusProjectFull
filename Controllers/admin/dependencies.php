<?php
/**
 * Controllers/Admin/.php
 *
 * This is the xxxxxx controller.
 *
 * @version 1.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */

namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Classes\Hash;
use TempusProjectCore\Classes\Code;
use TempusProjectCore\Core\Installer;
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Dependencies extends AdminController
{  
    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Dependencies';
    }
    public function index($data = null)
    {
        Debug::log("Controller initiated: " . __METHOD__ . ".");
        $installer = new Installer;
        $composerJson = $installer->getComposerJson();
        $requiredPackages = $composerJson['require'];
        foreach ($requiredPackages as $name => $version) {
            $versionsRequired[strtolower($name)] = $version;
        }

        $composerLock = $installer->getComposerLock();
        $installedPackages = $composerLock['packages'];
        foreach ($installedPackages as $package) {
            $name = strtolower($package['name']);
            $versionsInstalled[$name] = $package;
        }

        foreach ($versionsInstalled as $package) {
            $name = strtolower($package['name']);
            if (!empty($versionsRequired[$name])) {
                $versionsInstalled[$name]['requiredVersion'] = $versionsRequired[$name];
            } else {
                $versionsInstalled[$name]['requiredVersion'] = 'sub-dependency';
            }
            $out[] = (object) $versionsInstalled[$name];
        }
        $this->view('admin.dependencies', $out);
        exit();
    }
}
