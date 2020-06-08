<?php
/**
 * Models/session.php
 *
 * This model is used for the modification and management of the session data.
 * It also acts as an interpreter for the DB.
 *
 * Notes: After refactor, the sessions will use ID's for short term, and Cookies
 * will use the token for long term storage
 *
 * @version 3.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Check;
use TempusProjectCore\Classes\Code;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Functions\Routes;
use TempusProjectCore\Classes\DB;
use TempusProjectCore\Classes\Session;
use TempusProjectCore\Classes\Cookie;

class Sessions extends Controller
{
    protected static $group;
    protected static $user;
    protected static $activeSession = false;

    /**
     * The model constructor.
     */
    public function __construct()
    {
        Debug::log('Model Constructed: '.get_class($this));
    }

    /**
     * Returns the current model version.
     *
     * @return string - the correct model version
     */
    public static function modelVersion()
    {
        return '3.0.0';
    }
    
    /**
     * Returns an array of models required to run this model without error.
     *
     * @return array - An array of models
     */
    public static function requiredModels()
    {
        $required = [
            'user',
            'group'
        ];
        return $required;
    }

    /**
     * Tells the installer which types of integrations your model needs to install.
     *
     * @return array - Install flags
     */
    public static function installFlags()
    {
        $flags = [
            'installDB' => true,
            'installPermissions' => false,
            'installConfigs' => true,
            'installResources' => false,
            'installPreferences' => false
        ];
        return $flags;
    }

    /**
     * This function is used to install database structures needed for this model.
     *
     * @return boolean - The status of the completed install
     */
    public static function installDB()
    {
        self::$db->newTable('sessions');
        self::$db->addfield('userID', 'int', '5');
        self::$db->addfield('userGroup', 'int', '5');
        self::$db->addfield('expire', 'int', '10');
        self::$db->addfield('ip', 'varchar', '15');
        self::$db->addfield('hash', 'varchar', '80');
        self::$db->addfield('lastPage', 'varchar', '64');
        self::$db->addfield('username', 'varchar', '20');
        self::$db->addfield('token', 'varchar', '120');
        self::$db->createTable();
        return self::$db->getStatus();
    }
    
    /**
     * Install configuration options needed for the model.
     *
     * @return bool - If the configurations were added without error
     */
    public static function installConfigs()
    {
        Config::updateConfig('session', 'sessionPrefix', 'TTP_');
        Config::updateConfig('cookie', 'cookiePrefix', 'TTP_');
        Config::updateConfig('cookie', 'cookieExpiry', 604800);
        return Config::saveConfig();
    }

    /**
     * This method will remove all the installed model components.
     *
     * @return bool - if the uninstall was completed without error
     */
    public static function uninstall()
    {
        self::$db->removeTable('sessions');
        return true;
    }

    public function authenticate()
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        if (!isset(self::$group)) {
            self::$group = $this->model('group');
        }
        if (!$this->checkSession(Session::get('SessionID')) &&
            !$this->checkCookie(Cookie::get('RememberToken'), true)) {
            Debug::info('Sessions->authenticate - Could not authenticate cookie or session');
            return false;
        }
        self::$isLoggedIn = true;
        self::$activeUser = self::$user->get(self::$activeSession->userID);
        self::$activeGroup = self::$group->findById(self::$activeUser->userGroup);
        self::$activePrefs = json_decode(self::$activeUser->prefs);
        self::$isAdmin = self::$activeGroup->adminAccess;
        self::$isMod = self::$activeGroup->modAccess;
        self::$isMember = self::$activeGroup->memberAccess;
        return true;
    }
    /**
     * Check if a session exists, verifies the username,
     * password, and IP address to prevent forgeries.
     *
     * @param  int $id - The id of the session being checked.
     *
     * @return bool
     */
    public function checkSession($sessionID)
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        // @todo lets put this on some sort of realistic checking regime other than check everything every time
        if ($sessionID == false) {
            return false;
        }
        if (!Check::id($sessionID)) {
            return false;
        }
        $data = self::$db->get('sessions', ['ID', '=', $sessionID]);
        if ($data->count() == 0) {
            Debug::info('Session token not found.');
            return false;
        }
        $session = $data->first();
        if (self::$user->get($session->userID) === false) {
            Debug::info('User not found in DB.');
            $this->destroy($session->ID);
            return false;
        }
        $user = self::$user->data();
        if ($user->username != $session->username) {
            Debug::info("Usernames do not match.");
            $this->destroy($session->ID);
            return false;
        }
        if ($user->password != $session->hash) {
            Debug::info("Session Password does not match.");
            $this->destroy($session->ID);
            return false;
        }
        if (time() > $session->expire) {
            Debug::info("Session Expired.");
            $this->destroy($session->ID);
            return false;
        }
        if ($user->userGroup !== $session->userGroup) {
            Debug::info("Groups do not match.");
            $this->destroy($session->ID);
            return false;
        }
        if ($_SERVER['REMOTE_ADDR'] != $session->ip) {
            Debug::info("IP addresses do not match.");
            $this->destroy($session->ID);
            return false;
        }
        self::$activeSession = $session;
        return true;
    }

    /**
     * Checks the "remember me" cookie we use to identify
     * unique sessions across multiple visits. Checks that
     * the tokens match, checks the username as well as the
     * password from the database to ensure it hasn't been
     * modified elsewhere between visits.
     *
     * @param  string $token - The unique token saved as a cookie that is being checked.
     *
     * @return bool
     */
    public function checkCookie($cookieToken, $create = false)
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        if ($cookieToken == false) {
            return false;
        }
        $data = self::$db->get('sessions', ['token', '=', $cookieToken]);
        if (!$data->count()) {
            Debug::info("sessions->checkCookie - Session token not found.");

            return false;
        }
        $session = $data->first();
        if (self::$user->get($session->userID) === false) {
            Debug::info('sessions->checkCookie - could not find user by ID.');
            return false;
        }
        $user = self::$user->data();
        if ($user->username != $session->username) {
            Debug::info("sessions->checkCookie - Usernames do not match.");
            $this->destroy($session->ID);
            return false;
        }
        if ($user->password != $session->hash) {
            Debug::info("sessions->checkCookie - Session Password does not match.");
            $this->destroy($session->ID);
            return false;
        }
        if ($create) {
            return $this->newSession(null, false, false, $session->userID);
        }
        return true;
    }
    /**
     * Creates a new session from the data provided. The
     * expiration time is optional and will be set to the
     * system default if not provided.
     *
     * @param  int $ID     The User ID of the new session holder.
     * @param  int $expire The expiration time (in seconds).
     *
     * @return bool
     */
    public function newSession($expire = null, $override = false, $remember = false, $userID = null)
    {
        if (!isset(self::$user)) {
            self::$user = $this->model('user');
        }
        if (!isset($expire)) {
            // default Session Expiration is 24 hours
            $expire = (time() + (3600 * 24));
            Debug::log('Using default expiration time');
        }
        $lastPage = Routes::getUrl();
        if (self::$activeSession !== false) {
            // there is already an active session
            if ($override === false) {
                Debug::error('No need for a new session.');
                return false;
            }
            // We can override the active session
            $data = self::$db->get('sessions', ['userID', '=', self::$activeSession->ID]);
            if ($data->count()) {
                Debug::log('Deleting old session from db');
                $session = self::$db->first();
                $this->destroy($session->ID);
            }
        }
        if ($userID === null) {
            if (self::$activeUser === null) {
                Debug::info("Must provide user details to create a new session.");
                return false;
            }
            $userID = self::$activeUser->ID;
        }
        $userObject = self::$user->get($userID);
        if ($userObject === false) {
            Debug::info("User not found.");
            return false;
        }
        self::$db->insert('sessions', [
                            'username' => $userObject->username,
                            'hash' => $userObject->password,
                            'userGroup' => $userObject->userGroup,
                            'userID' => $userObject->ID,
                            'lastPage' => $lastPage,
                            'expire' => $expire,
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'token' => Code::genToken(),
                            ]);
        $data = self::$db->get('sessions', ['userID', '=', $userObject->ID]);
        $sessionData = self::$db->first();
        Session::put('SessionID', $sessionData->ID);
        if ($remember) {
            Cookie::put('RememberToken', $sessionData->token, $expire * 30);
        }
        self::$activeSession = $sessionData;
        return true;
    }

    /**
     * Function to update the users' current active page.
     *
     * @param  string $page The name of the page you are updating to.
     * @param  int|null $id  The ID of the session you are updating.
     *
     * NOTE: Current session assumed if no $id is provided.
     *
     * @return bool      true or false depending on success.
     */
    public function updatePage($page, $ID = null)
    {
        if (empty($ID)) {
            if (self::$activeSession === false) {
                Debug::info('Session::updatePage - Must provide session ID or have active session');

                return false;
            }
            $ID = self::$activeSession->ID;
        }
        if (!Check::id($ID)) {
            Debug::info('Session::updatePage - Invalid ID');

            return false;
        }
        if (!self::$db->update('sessions', $ID, ['lastPage' => $page])) {
            Debug::info('Session::updatePage - Failed to update database');

            return false;
        }
        return true;
    }

    /**
     * Destroy a session.
     *
     * @param  int $ID - The ID of the session you wish to destroy.
     *
     * @return bool     true if destroyed, false if it failed.
     */
    public function destroy($ID)
    {
        Session::delete('SessionID');
        Cookie::delete('RememberToken');
        if (!Check::id($ID)) {
            Debug::info('Session::destroy - Invalid ID');
            return false;
        }
        $data = self::$db->get('sessions', ['ID', '=', $ID]);
        if (!$data->count()) {
            Debug::info('Session::destroy - Session not found in DB');

            return false;
        }
        self::$db->delete('sessions', ['ID', '=', $ID]);
        self::$activeSession = false;

        return true;
    }
}
