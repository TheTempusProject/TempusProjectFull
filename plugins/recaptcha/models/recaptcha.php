<?php
/**
 * models/recaptcha.php
 *
 * This class is for the use and management of google's recapcha.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use ReCaptcha\ReCaptcha as GoogleReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;
use ReCaptcha\RequestMethod\Post;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Core\Model;

class ReCaptcha extends Model
{
    public static $configName = "recaptcha";
    protected static $errors = null;
    protected $recaptcha;
    protected static $enabled = null;
    protected static $privateKey = null;
    protected static $siteKey = null;
    protected static $request = null;
    protected static $requestMethod = null;
    protected static $sendIP = null;

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
     * Tells the installer which types of integrations your model needs to install.
     *
     * @return array - Install flags
     */
    public static function installFlags()
    {
        $flags = [
            'installConfigs' => true
        ];
        return $flags;
    }

    /**
     * Install configuration options needed for the model.
     *
     * @return bool - If the configurations were added without error
     */
    public static function installConfigs()
    {
        Config::addConfigCategory(self::$configName);
        Config::addConfig(self::$configName, 'siteKey', '');
        Config::addConfig(self::$configName, 'privateKey', '');
        Config::addConfig(self::$configName, 'sendIP', false);
        Config::addConfig(self::$configName, 'enabled', false);
        Config::addConfig(self::$configName, 'method', "curlPost");
        return Config::saveConfig();
    }

    public function verify($hash)
    {
        if (self::$enabled === false) {
            Debug::warn('Recaptcha is disabled.');
            return true;
        }
        self::$errors = null;
        $this->recaptcha = new GoogleReCaptcha(self::$privateKey, self::$request);

        if (self::$sendIP) {
            $response = $this->recaptcha->verify($hash, $_SERVER['REMOTE_ADDR']);
        } else {
            $response = $this->recaptcha->verify($hash);
        }
        if (!$response->isSuccess()) {
            self::$errors = $response->getErrorCodes();
            return false;
        }
        return true;
    }

    public function load()
    {
        if (self::$enabled === null) {
            $mods = apache_get_modules();
            if (!in_array('mod_rewrite', $mods)) {
                self::$enabled = false;
            }
            self::$enabled = Config::get(self::$configName . '/enabled');
            self::$privateKey = Config::get(self::$configName . '/privateKey');
            self::$siteKey = Config::get(self::$configName . '/siteKey');
            self::$sendIP = Config::get(self::$configName . '/sendIP');
            self::$requestMethod = Config::get(self::$configName . '/method');
            if (self::$requestMethod == "curlPost") {
                self::$request = new CurlPost();
            } else {
                self::$request = new Post();
            }
        }
        return self::$enabled;
    }

    public function enabled()
    {
        if (self::$enabled == null) {
            $this->load();
        }
        return self::$enabled;
    }

    public function getErrors()
    {
        return self::$errors;
    }
}
