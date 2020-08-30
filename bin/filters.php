<?php
/**
 * App/Filters.php
 *
 * This class is used in conjunction with TempusProjectCore\Core\Template
 * to house filters used by the framework. Filters provide another way to
 * parse the views and inject information before the views are displayed
 * on the front end. These filters can be enabled globally or can be used
 * individually. See TempusProjectCore\Core\Template->filter for more info.
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

class Filters
{
    public static function defaultFilters()
    {
        $defaultFilters = [
            'bbCode'          => false,
            'icons'           => false,
            'listComponents'  => false,
            'mentions'        => false,
            'hashtags'        => false,
            'comments'        => false,
            'formComponents'  => false
        ];
    }
    public static function bbCode()
    {
        $filter = [
            '#\[b\](.*?)\[/b\]#is'               => '<b>$1</b>',
            '#\[p\](.*?)\[/p\]#is'               => '<p>$1</p>',
            '#\[i\](.*?)\[/i\]#is'               => '<i>$1</i>',
            '#\[u\](.*?)\[/u\]#is'               => '<u>$1</u>',
            '#\[s\](.*?)\[/s\]#is'               => '<del>$1</del>',
            '#\[code\](.*?)\[/code\]#is'         => '<code>$1</code>',
            '#\[color=(.*?)\](.*?)\[/color\]#is' => "<font color='$1'>$2</font>",
            '#\[img\](.*?)\[/img\]#is'           => "<img src='$1'>",
            '#\[url=(.*?)\](.*?)\[/url\]#is'     => "<a href='$1'>$2</a>",
            '#\[quote=(.*?)\](.*?)\[/quote\]#is' => "<blockquote cite='$1'>$2</blockquote>"
        ];
    }
    public static function icons()
    {
        $filter = [
            '#\(c\)#is'                          => '&#10004;',
            '#\(x\)#is'                          => '&#10006;',
            '#\(!\)#is'                          => '&#10069;',
            '#\(\?\)#is'                         => '&#10068;'
        ];
    }
    public static function listComponents()
    {
        $filter = [
            '#\[list\](.*?)\[/list\]#is'         => '<ul>$1</ul>',
            '#\(\.\)(.*)$#m'                     => '<li>$1</li>'
        ];
    }
    public static function mentions()
    {
        $filter = [
            '/(^|\s)@(\w*[a-zA-Z_]+\w*)/'        => ' <a href="http://twitter.com/search?q=%40\2">@\2</a>'
        ];
    }
    public static function hashtags()
    {
        $filter = [
            '/(^|\s)#(\w*[a-zA-Z_]+\w*)/'        => ' <a href="http://twitter.com/search?q=%23\2">#\2</a>'
        ];
    }
    public static function comments()
    {
        $filter = [
            '#/\*.*?\*/#s'                       => null,
            '#(?<!:)//.*#'                       => null
        ];
    }
    public static function formComponents()
    {
        $filter = [
            "#{CHECKED:(.*?)=(.*?)}#s"           => null
        ];
    }
}
