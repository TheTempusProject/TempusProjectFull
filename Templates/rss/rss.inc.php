<?php
/**
 * Templates/rss/rss.inc.php
 *
 * This is the loader for the rss template.
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

class RssLoader extends Controller
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
        $this->components["COPY"] = Template::standardView('copy');
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
