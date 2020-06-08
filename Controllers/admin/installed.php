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

use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Issue;
use TempusProjectCore\Classes\Input;
use TempusProjectCore\Core\Installer;
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Installed extends AdminController
{
    public $installer = null;

    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Installed';
        $this->installer = new Installer;
    }
    public function index($data = null)
    {
        $models = $this->installer->getModelVersionList();
        foreach ($models as $model) {
            $modelArray = (array) $model;
            $node = $this->installer->getNode($model->name);
            if ($node === false) {
                $node = [
                    'name' => $model->name,
                    'installDate' => '',
                    'lastUpdate' => '',
                    'installStatus' => 'not installed',
                    'installedVersion' => '',
                    'installDB' => '',
                    'installPermissions' => '',
                    'installConfigs' => '',
                    'installResources' => '',
                    'installPreferences' => '',
                    'currentVersion' => '',
                    'version' => $this->installer->getModelVersion($model->name)
                ];
            }
            $out[] = (object) array_merge($modelArray, $node);
        }
        $this->view('admin.installed', $out);
        exit();
    }
    public function viewModel($data = null)
    {
        $node = $this->installer->getNode($data);
        if ($node === false) {
            $out = [
                'name' => $data,
                'installDate' => '',
                'lastUpdate' => '',
                'installStatus' => 'not installed',
                'currentVersion' => '',
                'installedVersion' => '',
                'installDB' => '',
                'installPermissions' => '',
                'installConfigs' => '',
                'installResources' => '',
                'installPreferences' => '',
                'version' => ''
            ];
        } else {
            $out = array_merge(['version' => $this->installer->getModelVersion($data)], (array) $node);
        }
        $this->view('admin.installedView', $out);
        exit();
    }
    public function install($data = null)
    {
        self::$template->set('MODEL', $data);
        if (!Input::exists('installHash')) {
            $this->view('admin.install');
            exit();
        }
        if (!$this->installer->installModel($data)) {
            Issue::error('There was an error with the Installation.', $this->installer->getErrors());
        }
        $this->index();
    }
    public function uninstall($data = null)
    {
        self::$template->set('MODEL', $data);
        if (!Input::exists('uninstallHash')) {
            $this->view('admin.uninstall');
            exit();
        }
        if (!$this->installer->uninstallModel($data)) {
            Issue::error('There was an error with the uninstall.', $this->installer->getErrors());
        }
        $this->index();
    }
}
