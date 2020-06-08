<?php
/**
 * App/Forms.php
 *
 * This class is used in conjunction with TempusProjectCore\Classes\Check
 * to house complete form verification in one location. You can utilize the
 * Check classes error reporting to easily define exactly what feedback you
 * would like to provide in response to any step of the validation.
 *
 * @version 1.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject;

use TempusProjectCore\Classes\Input as Input;
use TempusProjectCore\Classes\Check as Check;

class Forms
{
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installStart()
    {
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function passwordResetCode()
    {
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installAgreement()
    {
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installCheck()
    {
        if (!Check::uploads()) {
            Check::addUserError('Uploads are disabled.');
            return false;
        }
        if (!Check::php()) {
            Check::addUserError('PHP version is too old.');
            return false;
        }
        if (!Check::sessions()) {
            Check::addUserError('There is an error with Sessions.');
            return false;
        }
        if (!Check::mail()) {
            Check::addUserError('PHP mail is not enabled.');
            return false;
        }
        if (!Check::safe()) {
            Check::addUserError('Safe mode is enabled.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installConfigure()
    {
        if (!Check::db(Input::post('dbHost'), Input::post('dbName'), Input::post('dbUsername'), Input::post('dbPassword'))) {
            Check::addUserError('DB connection error.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installhtaccess()
    {
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installModels()
    {
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installResources()
    {
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }
    /**
     * Validates the installer form.
     *
     * @return boolean
     */
    public static function installAdminUser()
    {
        if (!Check::username(Input::post('newUsername'))) {
            Check::addUserError('Invalid username.');
            return false;
        }
        if (!Check::password(Input::post('userPassword'))) {
            Check::addUserError('Invalid password.');
            return false;
        }
        if (Input::post('userPassword') !== Input::post('userPassword2')) {
            Check::addUserError('Passwords do not match.');
            return false;
        }
        if (Input::post('userEmail') !== Input::post('userEmail2')) {
            Check::addUserError('Emails do not match.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }

    /**
     * Validates the registration form.
     *
     * @return boolean
     */
    public static function register()
    {
        if (!Check::username(Input::post('username'))) {
            Check::addUserError('Invalid username.');
            return false;
        }
        if (!Check::password(Input::post('password'))) {
            Check::addUserError('Invalid password.');
            return false;
        }
        if (!Check::email(Input::post('email'))) {
            Check::addUserError('Invalid Email.');
            return false;
        }
        if (!Check::noEmailExists(Input::post('email'))) {
            Check::addUserError('A user with that email is already registered.');
            return false;
        }
        if (Input::post('password') !== Input::post('password2')) {
            Check::addUserError("Passwords do not match.");
            return false;
        }
        if (Input::post('email') !== Input::post('email2')) {
            Check::addUserError("Emails do not match.");
            return false;
        }
        if (Input::post('terms') != '1') {
            Check::addUserError("You must agree to the terms of service.");
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    public static function login()
    {
        if (!Check::username(Input::post('username'))) {
            Check::addUserError('Invalid username.');
            return false;
        }
        if (!Check::password(Input::post('password'))) {
            Check::addUserError('Invalid password.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    /**
     * Validates the feedback form.
     *
     * @return boolean
     */
    public static function feedback()
    {
        if (!Input::exists('name')) {
            Check::addUserError('You must provide a name.');
            return false;
        }
        if (!Check::name(Input::post('name'))) {
            Check::addUserError('Invalid name.');
            return false;
        }
        if (Input::exists('feedbackEmail') && !Check::email(Input::post('feedbackEmail'))) {
            Check::addUserError('Invalid Email.');
            return false;
        }
        if (Input::post('entry') == '') {
            Check::addUserError('Feedback cannot be empty.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    /**
     * Validates the bug report form.
     *
     * @return boolean
     */
    public static function bugreport()
    {
        if (!Check::url(Input::post('url'))) {
            Check::addUserError('Invalid url.');
            return false;
        }
        if (!Check::url(Input::post('ourl'))) {
            Check::addUserError('Invalid original url.');
            return false;
        }
        if (!Check::tf(Input::post('repeat'))) {
            Check::addUserError('Invalid repeat value.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    /**
     * Validates the email change form.
     *
     * @return boolean
     */
    public static function changeEmail()
    {
        if (!Check::email(Input::post('email'))) {
            Check::addUserError('Invalid Email.');
            return false;
        }
        if (Input::post('email') !== Input::post('email2')) {
            Check::addUserError("Emails do not match.");
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }

    /**
     * Validates the password change form.
     *
     * @return boolean
     */
    public static function changePassword()
    {
        if (!Check::password(Input::post('password'))) {
            Check::addUserError('Invalid password.');
            return false;
        }
        if (Input::post('password') !== Input::post('password2')) {
            Check::addUserError('Passwords do not match.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        
        return true;
    }

    /**
     * Validates the password reset form.
     *
     * @return boolean
     */
    public static function passwordReset()
    {
        if (!Check::password(Input::post('password'))) {
            Check::addUserError('Invalid password.');
            return false;
        }
        if (Input::post('password') !== Input::post('password2')) {
            Check::addUserError('Passwords do not match.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    /**
     * Validates the subscribe form.
     *
     * @return boolean
     */
    public static function subscribe()
    {
        if (!Check::email(Input::post('email'))) {
            Check::addUserError('Invalid email.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    /**
     * Validates the unsubscribe form.
     *
     * @return boolean
     */
    public static function unsubscribe()
    {
        if (!Check::email(Input::post('email'))) {
            Check::addUserError('Invalid email.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    public static function emailConfirmation()
    {
        if (!Input::exists('confirmationCode')) {
            Check::addUserError('No confirmation code provided.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    public static function confirmationResend()
    {
        if (!Input::exists('resendConfirmation')) {
            Check::addUserError('Confirmation not provided.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }
    /**
     * Validates the reply message form.
     *
     * @return boolean
     */
    public static function replyMessage()
    {
        if (!Input::exists('message')) {
            Check::addUserError('Reply cannot be empty.');
            return false;
        }
        if (!Input::exists('messageID')) {
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }
    /**
     * Validates the new message form.
     *
     * @return boolean
     */
    public static function newMessage()
    {
        if (!Input::exists('toUser')) {
            Check::addUserError('You must specify a user to send the message to.');
            return false;
        }
        if (!Input::exists('subject')) {
            Check::addUserError('You must have a subject for your message.');
            return false;
        }
        if (!Input::exists('message')) {
            Check::addUserError('No message entered.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    /**
     * Validates the new comment form.
     *
     * @return boolean
     */
    public static function newComment()
    {
        if (!Input::exists('comment')) {
            Check::addUserError('You cannot post a blank comment.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }

    /**
     * Validates the edit comment form.
     *
     * @return boolean
     */
    public static function editComment()
    {
        if (!Input::exists('comment')) {
            Check::addUserError('You cannot post a blank comment.');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }
    
    /**
     * Validates the user preferences form.
     *
     * @return boolean
     */
    public static function userPrefs()
    {
        // @todo make this a real check
        if (!Input::exists('timeFormat')) {
            Check::addUserError('You must specify timeFormat');
            return false;
        }
        if (!Input::exists('pageLimit')) {
            Check::addUserError('You must specify pageLimit');
            return false;
        }
        if (!Input::exists('gender')) {
            Check::addUserError('You must specify gender');
            return false;
        }
        if (!Input::exists('dateFormat')) {
            Check::addUserError('You must specify dateFormat');
            return false;
        }
        if (!Input::exists('timezone')) {
            Check::addUserError('You must specify timezone');
            return false;
        }
        if (!Input::exists('updates')) {
            Check::addUserError('You must specify updates');
            return false;
        }
        if (!Input::exists('newsletter')) {
            Check::addUserError('You must specify newsletter');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }
    /**
     * Validates the new blog post form.
     *
     * @return boolean
     */
    public static function newBlogPost()
    {
        if (!Input::exists('title')) {
            Check::addUserError('You must specify title');
            return false;
        }
        if (!Check::dataTitle(Input::post('title'))) {
            Check::addUserError('Invalid title');
            return false;
        }
        if (!Input::exists('blogPost')) {
            Check::addUserError('You must specify a post');
            return false;
        }
        /** You cannot use the token check due to how tinymce reloads the page
        if (!Check::token()) {
            return false;
        }
        */
        return true;
    }
    public static function editBlogPost()
    {
        if (!Input::exists('title')) {
            Check::addUserError('You must specify title');
            return false;
        }
        if (!Check::dataTitle(Input::post('title'))) {
            Check::addUserError('Invalid title');
            return false;
        }
        if (!Input::exists('blogPost')) {
            Check::addUserError('You must specify a post');
            return false;
        }
        /** You cannot use the token check due to how tinymce reloads the page
        if (!Check::token()) {
            return false;
        }
        */
        return true;
    }
    /**
     * Validates the new subscription form.
     *
     * @return boolean
     */
    public static function newSubscription()
    {
        if (!Check::token()) {
            return false;
        }
        return true;
    }
    /**
     * Validates the new group form.
     *
     * @return boolean
     */
    public static function newGroup()
    {
        if (!Input::exists('name')) {
            Check::addUserError('You must specify a name');
            return false;
        }
        if (!Check::dataTitle(Input::exists('name'))) {
            Check::addUserError('invalid group name');
            return false;
        }
        if (!Input::exists('pageLimit')) {
            Check::addUserError('You must specify a pageLimit');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }
    /**
     * Validates the new group form.
     *
     * @return boolean
     */
    public static function editGroup()
    {
        if (!Input::exists('name')) {
            Check::addUserError('You must specify a name');
            return false;
        }
        if (!Check::dataTitle(Input::exists('name'))) {
            Check::addUserError('invalid group name');
            return false;
        }
        if (!Input::exists('pageLimit')) {
            Check::addUserError('You must specify a pageLimit');
            return false;
        }
        if (!Check::token()) {
            return false;
        }
        return true;
    }
}
