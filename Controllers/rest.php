<?php
/**
 * Controllers/rest.php
 *
 * This is the rest API controller.
 *
 * @version 3.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */

namespace TheTempusProject\Controllers;

use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Classes\Debug as Debug;

class Rest extends Controller
{
    public function __construct()
    {
        Debug::log('Controller Constructing: ' . get_class($this));
        self::$template->noFollow();
        self::$template->noIndex();
        self::$template->setTemplate('rest');
    }

    public function __destruct()
    {
        Debug::log('Controller Destructing: ' . get_class($this));
        $this->build();
        Debug::closeAllGroups();
    }

    public function ping()
    {

    }
}
