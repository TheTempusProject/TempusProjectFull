<?php
/**
 * models/user.php
 *
 * This class is used for the manipulation of the user database table.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Models;

use TempusProjectCore\Classes\Check;
use TempusProjectCore\Core\Controller;
use TempusProjectCore\Classes\Debug;
use TempusProjectCore\Classes\Config;
use TempusProjectCore\Classes\DB;
use TempusProjectCore\Classes\Session as Session;
use TempusProjectCore\Classes\Preference;
use TempusProjectCore\Functions\Routes;
use TempusProjectCore\Classes\CustomException;
use TempusProjectCore\Classes\Hash;

class User extends Controller
{
    protected static $session;
    protected static $group;
    protected static $log;
    protected $usernames;
    protected $avatars;
    protected $data = [];

    /**
     * Returns the current model version.
     *
     * @return string - the correct model version
     */
    public static function modelVersion()
    {
        return '2.0.0';
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
            'session',
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
            'installConfigs' => false,
            'installResources' => false,
            'installPreferences' => true
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
        self::$db->newTable('users');
        self::$db->addfield('registered', 'int', '10');
        self::$db->addfield('terms', 'int', '1');
        self::$db->addfield('confirmed', 'int', '1');
        self::$db->addfield('userGroup', 'int', '11');
        self::$db->addfield('lastLogin', 'int', '10');
        self::$db->addfield('username', 'varchar', '16');
        self::$db->addfield('password', 'varchar', '80');
        self::$db->addfield('email', 'varchar', '75');
        self::$db->addfield('name', 'varchar', '20');
        self::$db->addfield('confirmationCode', 'varchar', '80');
        self::$db->addfield('prefs', 'text', '');
        self::$db->createTable();
        return self::$db->getStatus();
    }

    /**
     * Install preferences needed for the model.
     *
     * @return bool - If the preferences were added without error
     */
    public static function installPreferences()
    {
        Preference::addPref('gender', "unspecified");
        Preference::addPref('email', "true");
        Preference::addPref('newsletter', "true");
        Preference::addPref('avatar', "Images/defaultAvatar.png");
        return Preference::savePrefs(true);
    }

    /**
     * This method will remove all the installed model components.
     *
     * @return bool - if the uninstall was completed without error
     */
    public static function uninstall()
    {
        self::$db->removeTable('users');
        Preference::removePref('gender');
        Preference::removePref('email');
        Preference::removePref('newsletter');
        Preference::removePref('avatar');
        Preference::savePrefs(true);
        return true;
    }

    /**
     * Since we need a cache of the usernames, we use this function
     * to find/return all usernames based on ID.
     *
     * @param  $ID - The ID of the user you are looking for.
     *
     * @return string - Either the username or unknown will be returned.
     */
    public function getUsername($ID)
    {
        if (!Check::id($ID)) {
            return false;
        }
        if (!isset($this->usernames[$ID])) {
            $user = self::get($ID);
            if ($user !== false) {
                $this->usernames[$ID] = $user->username;
            } else {
                $this->usernames[$ID] = 'Unknown';
            }
        }
        return $this->usernames[$ID];
    }

    /**
     * Since we need a cache of the usernames, we use this function
     * to find/return all usernames based on ID.
     *
     * @param  $ID - The ID of the user you are looking for.
     *
     * @return string - Either the username or unknown will be returned.
     */
    public function getID($username)
    {
        if (!Check::username($username)) {
            return false;
        }
        $user = self::get($username);
        if ($user !== false) {
            return $user->ID;
        } else {
            return 0;
        }
    }

    /**
     * Since we need a cache of the usernames, we use this function
     * to find/return all usernames based on ID.
     *
     * @param  $ID - The ID of the user you are looking for.
     *
     * @return string - Either the username or unknown will be returned.
     *
     * @todo  add this to users model
     */
    public function getAvatar($ID)
    {
        if (!Check::id($ID)) {
            return false;
        }
        if (!isset($this->avatars[$ID])) {
            if ($this->get($ID)) {
                $this->avatars[$ID] = self::data()->avatar;
            } else {
                $this->avatars[$ID] = '{BASE}Images/defaultAvatar.png';
            }
        }
        return $this->avatars[$ID];
    }

    /**
     * Function to delete the specified user.
     *
     * @param  int|array $ID the log ID or array of ID's to be deleted
     *
     * @return bool
     */
    public function delete($data)
    {
        if (!isset(self::$log)) {
            self::$log = $this->model('log');
        }
        foreach ($data as $instance) {
            if (!is_array($data)) {
                $instance = $data;
                $end = true;
            }
            if (!Check::id($instance)) {
                $error = true;
            }
            if (self::$activeUser->ID == $instance) {
                Debug::info('Attempt to delete own account.');
                return false;
            }
            self::$db->delete('users', ['ID', '=', $instance]);
            self::$log->admin("Deleted user: $instance");
            Debug::info("User deleted: $instance");
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
     * This function is responsible for all the business logic of logging in.
     *
     * @param  string  $username - The username being used to login.
     * @param  string  $password - The un-hashed password.
     * @param  boolean $remember - Whether the user wishes to be remembered or not.
     *
     * @return bool            Returns true or false depending on success.
     */
    public function logIn($username, $password, $remember = false)
    {
        if (!isset(self::$log)) {
            self::$log = $this->model('log');
        }
        if (!isset(self::$session)) {
            self::$session = $this->model('sessions');
        }
        Debug::group('login', 1);
        if (!Check::username($username)) {
            Debug::warn('Invalid Username.');
            return false;
        }
        if (!$this->get($username)) {
            self::$log->login(0, "User not found: $username");
            Debug::warn("User not found: $username");
            return false;
        }
        // login attempts protection.
        $timeLimit = (time() - 3600);
        $limit = Config::get('main/loginLimit');
        $user = $this->data();
        if ($limit > 0) {
            $limitCheck = self::$db->get('logs', ['source', '=', 'login', 'AND', 'userID', '=', $user->ID, 'AND', 'time', '>=', $timeLimit, 'AND', 'action', '!=', 'pass']);
            if ($limitCheck->count() >= $limit) {
                Debug::info('login: Limit reached.', 1);
                self::$log->login($user->ID, 'Too many failed attempts.');
                Debug::warn('Too many failed login attempts, please try again later.');
                return false;
            }
        }
        if (!Check::password($password)) {
            Debug::warn('Invalid password.');
            self::$log->login($user->ID, 'Invalid Password.');
            return false;
        }
        if (!Hash::check($password, $user->password)) {
            Debug::warn('Pass hash does not match.');
            self::$log->login($user->ID, 'Wrong Password.');
            return false;
        }
        self::$session->newSession(null, true, $remember, $user->ID);
        self::$log->login($this->data()->ID, 'pass');
        $set = ['lastLogin' => time()];
        $this->update($set, $this->data()->ID);
        Debug::gend();
        return true;
    }

    /**
     * Function for logging out a user.
     *
     * @return null
     */
    public function logOut()
    {
        if (!isset(self::$session)) {
            self::$session = $this->model('sessions');
        }
        Debug::group("Logout", 1);
        self::$session->destroy(Session::get(self::$sessionPrefix . 'SessionToken'));
        self::$isLoggedIn = false;
        self::$isMember = false;
        self::$isMod = false;
        self::$isAdmin = false;
        self::$activeUser = null;
        Debug::info("User has been logged out.");
        Debug::gend();
        return null;
    }

    /**
     * Function to change a user's password.
     *
     * @param  string $code     - The confirmation code required from the password email.
     * @param  string $password - The new password for the user's account.
     *
     * @return bool
     */
    public function changePassword($code, $password)
    {
        if (!Check::password($password)) {
            return false;
        }
        $data = self::$db->get('users', ['confirmationCode', '=', $code]);
        if ($data->count()) {
            $this->data = $data->first();
            $this->update([
                    'password' => Hash::make($password),
                    'confirmationCode' => '',
                ], $this->data->ID);
            return true;
        }
        return false;
    }

    /**
     * Compiles a list of all users, allowing for filtering the list.
     *
     * @todo
     *
     * @param  array $filter - A filter to be applied to the users list.
     *
     * @return bool|object - Depending on success.
     */
    public function userList($filter = null)
    {
        if (!empty($filter)) {
            switch ($filter) {
                case 'newsletter':
                    $data = self::$db->search('users', 'prefs', 'newsletter":"true');
                    break;
                default:
                    $data = self::$db->get('users', "*");
                    break;
            }
        } else {
            $data = self::$db->get('users', "*");
        }
        if (!$data->count()) {
            return false;
        }
        return (object) $data->results();
    }

    /**
     * Compiles a list of recently registered users, allowing for filtering the list.
     *
     * @param  array $filter - A filter to be applied to the users list.
     *
     * @return bool|object - Depending on success.
     */
    public function recent($limit = null)
    {
        if (empty($limit)) {
            $data = self::$db->getpaginated('users', '*');
        } else {
            $data = self::$db->get('users', ['ID', '>', '0'], 'ID', 'DESC', [0, $limit]);
        }
        if (!$data->count()) {
            return false;
        }
        return (object) $data->results();
    }

    /**
     * This function is used to check a confirmation code for a user.
     *
     * @param  string $data - The confirmation code being checked.
     *
     * @return bool
     */
    public function checkCode($data)
    {
        $data = self::$db->get('users', ['confirmationCode', '=', $data]);
        if ($data->count() > 0) {
            return true;
        }
        Debug::error('User confirmation code not found.');
        return false;
    }

    /**
     * Generates a new confirmation code for the user
     * specified and updates the user's DB entry with
     * the new code.
     *
     * @param  int $data - The user ID to update the confirmation code for.
     *
     * @return bool
     */
    public function newCode($data)
    {
        $data = self::$db->get('users', ['ID', '=', $data]);
        if ($data->count() == 0) {
            return false;
        }
        $this->data = $data->first();
        $Ccode = md5(uniqid());
        $this->update([
                'confirmationCode' => $Ccode,
            ], $this->data->ID);

        return true;
    }

    /**
     * This function is used for confirming a user's registration on the site.
     *
     * @param  string $data - The confirmation code sent to the user.
     *
     * @return bool
     */
    public function confirm($data)
    {
        $data = self::$db->get('users', ['confirmationCode', '=', $data]);
        if ($data->count()) {
            $this->data = $data->first();
            $this->update([
                    'confirmed' => 1,
                    'confirmationCode' => '',
                ], $this->data->ID);

            return true;
        }
        return false;
    }

    /**
     * Check if the specified user exists or not.
     *
     * @return bool - returns true or false depending on if the user was found or not.
     */
    public function exists()
    {
        return (!empty($this->data)) ? true : false;
    }

    /**
     * Function to get a user's info from an ID or username.
     *
     * @param  int|string $user - Either the username or user ID being searched for.
     *
     * @return bool|array
     */
    public function get($user)
    {
        $field = (ctype_digit($user)) ? 'ID' : 'username';
        if ($field == 'username') {
            if (!Check::username($user)) {
                Debug::info("modelUser->get Username improperly formatted.");

                return false;
            }
        } else {
            if (!Check::id($user)) {
                Debug::info("modelUser->get Invalid ID.");

                return false;
            }
        }
        $data = self::$db->get('users', [$field, '=', $user]);
        if (!$data->count()) {
            Debug::info("modelUser->get User not found: $user");

            return false;
        }
        $this->data = $data->first();
        $json = (array) json_decode($this->data->prefs, true);
        if ($this->data->confirmed == 1) {
            $this->data->confirmedText = 'Yes';
        } else {
            $this->data->confirmedText = 'No';
        }
        if ((empty($json['avatar'])) || ($json['avatar'] == 'defaultAvatar.png')) {
            $json['avatar'] = 'Images/defaultAvatar.png';
        }
        if (!isset(self::$group)) {
            self::$group = $this->model('group');
        }
        $group = self::$group->findById($this->data->userGroup);
        $json['groupName'] = $group->name;
        $this->data = (object) array_merge($json, (array) $this->data);
        return $this->data;
    }

    /**
     * Function for finding a User by email address.
     *
     * @param string $email - The email being searched for.
     *
     * @return bool
     */
    public function findByEmail($email)
    {
        if (Check::email($email)) {
            $data = self::$db->get('users', ['email', '=', $email]);
            if ($data->count()) {
                $this->data = $data->first();

                return true;
            }
        }
        Debug::error("modelUser->findByEmail - User not found by email: $email");

        return false;
    }

    /**
     * Create a User with the specified information.
     *
     * @param array $fields - The New User's data.
     *
     * @return  bool
     */
    public function create($fields = [])
    {
        if (empty($fields)) {
            return false;
        }
        $docLocation = Routes::getLocation('appPreferences');
        if ($docLocation->error) {
            $docLocation = Routes::getLocation('appPreferencesDefault');
            if ($docLocation->error) {
                $docLocation = Routes::getLocation('preferencesDefault');
            }
        }
        $decodedDefaultPrefs = json_decode(file_get_contents($docLocation->fullPath), true);
        $encodedDefaultPrefs = json_encode($decodedDefaultPrefs);
        $prefsJson = ['prefs' => $encodedDefaultPrefs];
        $userArray = array_merge($prefsJson, $fields);
        $userGroup = ['userGroup' => Config::get('group/defaultGroup')];
        $userArray = array_merge($userGroup, $userArray);
        if (!self::$db->insert('users', $userArray)) {
            Debug::error("User not created.");

            return false;
        }
        return true;
    }

    /**
     * Update a user's DB entry.
     *
     * @param array  $fields - The fields to be updated.
     * @param int $id     - The user ID being updated.
     *
     * @return  bool
     */
    public function update($fields = [], $ID = null)
    {
        if (!Check::id($ID)) {
            return false;
        }
        if (!self::$db->update('users', $ID, $fields)) {
            new CustomException('userUpdate');
            Debug::error("User: $ID not updated: $fields");

            return false;
        }
        return true;
    }

    /**
     * Update a user's preferences.
     *
     * @param array  $fields - The fields to be updated.
     * @param integer $id    - The user ID being updated.
     *
     * @return  boolean
     */
    public function updatePrefs($fields = [], $ID = null)
    {
        if (!Check::id($ID)) {
            return false;
        }
        $userData = self::get($ID);
        $prefsInput = json_decode($userData->prefs, true);
        foreach ($fields as $name => $value) {
            $prefsInput[$name] = $value;
        }
        $fields = ['prefs' => json_encode($prefsInput)];
        if (!self::$db->update('users', $ID, $fields)) {
            Debug::error("User: $ID not updated.");

            return false;
        }
        return true;
    }

    /**
     * Fetches an array for the currently selected user.
     *
     * @return array - An array of the user data
     */
    public function data()
    {
        return $this->data;
    }
}
