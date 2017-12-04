<?php
/**
 * Templates/admin/admin.inc.php
 *
 * This is the loader for the admin template.
 *
 * The template engine will automatically require this file and
 * initiate its constructor. Next it will call the values function
 * to load the components array into the Template Engine $values
 * array for later use.
 *
 * @version 1.0
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://TheTempusProject.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */

namespace TheTempusProject\Templates;

use TempusProjectCore\Core\Controller as Controller;
use TempusProjectCore\Core\Template as Template;
use TempusProjectCore\Classes\Config as Config;

class AdminLoader extends Controller
{
    /**
     * The array that will be loaded into Template::$values.
     *
     * @var array
     */
    private $components = [];

    /**
     * This is the function used to generate any components that may be
     * needed by this template.
     */
    public function __construct()
    {
        $this->components['LOGO'] = Config::get('main/logo');
        $this->components['FOOT'] = Template::standardView('foot');
        $this->components["COPY"] = Template::standardView('copy');
        if (self::$isLoggedIn) {
            $this->components['STATUS'] = Template::standardView('status.logged.in');
            $this->components['USERNAME'] = self::$activeUser->username;
        } else {
            $this->components['STATUS'] = Template::standardView('status.logged.out');
        }
        $this->components['ADMINNAV'] = Template::standardView('nav.admin');
        $this->components['ADMINNAV'] = Template::activePageSelect(null, null, $this->components['ADMINNAV']);
        $this->components['MAINNAV'] = Template::standardView('nav.main');
        $this->components['MAINNAV'] = Template::activePageSelect(null, null, $this->components['MAINNAV']);
    }

    /**
     * This is the default function used to retrieve any
     * components needed for this template.
     *
     * @return string - A serialized version of the components array.
     */
    public function values()
    {
        return serialize($this->components);
    }
}
