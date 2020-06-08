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
use TempusProjectCore\Classes\Image;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Core\Installer;
use TheTempusProject\Controllers\AdminController;

require_once 'AdminController.php';

class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$title = 'Admin - Settings';
        self::$group = $this->model('group');
    }
    public function index()
    {
        $installer = new Installer;
        $a = Input::exists('submit');
        self::$template->set('TIMEZONELIST', self::$template->standardView('timezoneDropdown'));
        if (Input::exists('logo') && Image::upload('logo', 'System')) {
            $logo = 'Uploads/Images/System/' . Image::last();
        } else {
            $logo = Config::get('main/logo');
        }
        if ($a) {
            Config::updateConfig('main', 'name', Input::post('name'));
            Config::updateConfig('main', 'template', Input::post('template'));
            Config::updateConfig('main', 'loginLimit', (int) Input::post('loginLimit'));
            Config::updateConfig('main', 'logo', $logo);
            Config::updateConfig('main', 'timezone', Input::post('timezone'));
            Config::updateConfig('main', 'pageLimit', (int) Input::post('pageLimit'));
            Config::updateConfig('uploads', 'enabled', Input::post('uploads'));
            Config::updateConfig('uploads', 'maxFileSize', (int) Input::post('fileSize'));
            Config::updateConfig('uploads', 'maxImageSize', (int) Input::post('imageSize'));
            Config::updateConfig('cookie', 'cookieExpiry', (int) Input::post('cookieExpiry'));
            Config::updateConfig('feedback', 'enabled', Input::post('logF'));
            Config::updateConfig('logging', 'errors', Input::post('logE'));
            Config::updateConfig('logging', 'logins', Input::post('logL'));
            Config::updateConfig('bugreports', 'enabled', Input::post('logBR'));
            Config::updateConfig('group', 'defaultGroup', Input::post('groupSelect'));
            Config::updateConfig('recaptcha', 'siteKey', Input::post('siteHash'));
            Config::updateConfig('recaptcha', 'privateKey', Input::post('privateHash'));
            Config::updateConfig('recaptcha', 'sendIP', Input::post('sendIP'));
            Config::updateConfig('recaptcha', 'enabled', Input::post('recaptcha'));
            Config::saveConfig();
        }
        $select = self::$template->standardView('admin.groupSelect', self::$group->listGroups());
        self::$template->set('groupSelect', $select);
        self::$template->set('LOGO', $logo);
        self::$template->set('NAME', $a ? Input::post('name') : Config::get('main/name'));
        self::$template->set('TEMPLATE', $a ? Input::post('template') : Config::get('main/template'));
        self::$template->set('maxFileSize', $a ? Input::post('fileSize') : Config::get('uploads/maxFileSize'));
        self::$template->set('maxImageSize', $a ? Input::post('imageSize') : Config::get('uploads/maxImageSize'));
        self::$template->set('cookieExpiry', $a ? Input::post('cookieExpiry') : Config::get('cookie/cookieExpiry'));
        self::$template->set('siteHash', $a ? Input::post('siteHash') : Config::get('recaptcha/siteKey'));
        self::$template->set('privateHash', $a ? Input::post('privateHash') : Config::get('recaptcha/privateKey'));
        self::$template->set('LIMIT', $a ? Input::post('loginLimit') : Config::get('main/loginLimit'));
        self::$template->selectOption($a ? Input::post('groupSelect') : Config::get('group/defaultGroup'));
        self::$template->selectOption($a ? Input::post('timezone') : Config::get('main/timezone'));
        self::$template->selectOption($a ? Input::post('pageLimit') : Config::get('main/pageLimit'));
        self::$template->selectRadio('feedback', $a ? Input::post('logF') : Config::getString('feedback/enabled'));
        self::$template->selectRadio('errors', $a ? Input::post('logE') : Config::getString('logging/errors'));
        self::$template->selectRadio('logins', $a ? Input::post('logL') : Config::getString('logging/logins'));
        self::$template->selectRadio('bugReports', $a ? Input::post('logBR') : Config::getString('bugreports/enabled'));
        self::$template->selectRadio('uploads', $a ? Input::post('uploads') : Config::getString('uploads/enabled'));
        self::$template->selectRadio('sendIP', $a ? Input::post('sendIP') : Config::getString('recaptcha/sendIP'));
        self::$template->selectRadio('recaptcha', $a ? Input::post('recaptcha') : Config::getString('recaptcha/enabled'));
        self::$template->set('securityHash', $installer->getNode('installHash'));
        $this->view('admin.settings');
        exit();
    }
}
