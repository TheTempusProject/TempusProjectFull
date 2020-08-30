<?php
/**
 * templates/rss/rss.inc.php
 *
 * This is the loader for the rss template.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Templates;

use TempusProjectCore\Core\Controller;
use TempusProjectCore\Core\Template;

class RssLoader extends Controller
{
    private $components = [];

    public function __construct()
    {
        $this->components["COPY"] = Template::standardView('copy');
    }

    public function values()
    {
        return serialize($this->components);
    }
}
