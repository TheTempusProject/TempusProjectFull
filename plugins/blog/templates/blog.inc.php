<?php
/**
 * templates/blog.inc.php
 *
 * This is the loader for the blog template.
 *
 * @version 2.0
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 * @link    https://TheTempusProject.com
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */
namespace TheTempusProject\Templates;

use TempusProjectCore\Core\Template;
use TempusProjectCore\Core\TPCore;
use TempusProjectCore\Classes\Config;

class BlogLoader extends TPCore
{
    private $components = [];

    public function __construct()
    {
        $blog = $this->model('blog');
        $this->components['LOGO'] = Config::get('main/logo');
        $this->components['FOOT'] = Template::standardView('foot');
        $this->components["COPY"] = Template::standardView('copy');
        $this->components["SIDEBAR"] = Template::standardView('blog.sidebar', $blog->recent(5));
        $this->components["SIDEBAR2"] = Template::standardView('blog.sidebar2', $blog->archive());
        if (self::$isLoggedIn) {
            $this->components['STATUS'] = Template::standardView('navigation.statusIn');
            $this->components['USERNAME'] = self::$activeUser->username;
        } else {
            $this->components['STATUS'] = Template::standardView('navigation.statusOut');
        }
        $this->components['MAINNAV'] = Template::standardView('navigation.main');
        $this->components['MAINNAV'] = Template::activePageSelect(null, null, $this->components['MAINNAV']);
    }

    public function values()
    {
        return serialize($this->components);
    }
}
