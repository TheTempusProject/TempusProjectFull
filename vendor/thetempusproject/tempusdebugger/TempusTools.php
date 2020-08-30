<?php
/**
 * TempusTools.php
 *
 * description
 *
 * @version 1.1
 *
 * @author  Joey Kimsey <JoeyKimsey@thetempusproject.com>
 *
 * @link    https://.com
 *
 * @license https://opensource.org/licenses/MIT [MIT LICENSE]
 */

namespace TempusDebugger;

if (!class_exists('TempusDebugger', false)) {
    require_once 'TempusDebugger.php';
}

/**
 * Sends the given data to the TempusTools Chrome Extension.
 * The data can be displayed in devtools.
 *
 * @param mixed $Object
 *
 * @return true
 *
 * @throws Exception
 */
function tt()
{
    $instance = TempusDebugger::getInstance(true);
  
    $args = func_get_args();
    return call_user_func_array(array($instance, 'tt'), $args);
}


class TempusTools
{
    /**
     * Set an Insight console to direct all logging calls to
     *
     * @param object $console The console object to log to
     * @return void
     */
    public static function setLogToInsightConsole($console)
    {
        TempusDebugger::getInstance(true)->setLogToInsightConsole($console);
    }

    /**
     * Enable and disable logging to TempusTools
     *
     * @param boolean $enabled TRUE to enable, FALSE to disable
     * @return void
     */
    public static function setEnabled($enabled)
    {
        TempusDebugger::getInstance(true)->setEnabled($enabled);
    }
  
    /**
     * Check if logging is enabled
     *
     * @return boolean TRUE if enabled
     */
    public static function getEnabled()
    {
        return TempusDebugger::getInstance(true)->getEnabled();
    }
  
    /**
     * Specify a filter to be used when encoding an object
     *
     * Filters are used to exclude object members.
     *
     * @param string $class The class name of the object
     * @param array $filter An array or members to exclude
     * @return void
     */
    public static function setObjectFilter($class, $filter)
    {
        TempusDebugger::getInstance(true)->setObjectFilter($class, $filter);
    }
  
    /**
     * Set some options for the library
     *
     * @param array $options The options to be set
     * @return void
     */
    public static function setOptions($options)
    {
        TempusDebugger::getInstance(true)->setOptions($options);
    }

    /**
     * Get options for the library
     *
     * @return array The options
     */
    public static function getOptions()
    {
        return TempusDebugger::getInstance(true)->getOptions();
    }

    /**
     * Log object to console
     *
     * @param mixed $object
     * @return true
     * @throws Exception
     */
    public static function send()
    {
        $args = func_get_args();
        return call_user_func_array(array(TempusDebugger::getInstance(true), 'tt'), $args);
    }

    /**
     * Start a group for following messages
     *
     * Options:
     *   Collapsed: [true|false]
     *   Color:     [#RRGGBB|ColorName]
     *
     * @param string $name
     * @param array $options OPTIONAL Instructions on how to log the group
     * @return true
     */
    public static function group($name, $options = null)
    {
        return TempusDebugger::getInstance(true)->group($name, $options);
    }

    /**
     * Ends a group you have started before
     *
     * @return true
     * @throws Exception
     */
    public static function groupEnd()
    {
        return self::send(null, null, TempusDebugger::GROUP_END);
    }

    /**
     * Log object with label to the console
     *
     * @param mixes $object
     * @param string $label
     * @return true
     * @throws Exception
     */
    public static function log($object, $label = null)
    {
        return self::send($object, $label, TempusDebugger::LOG);
    }

    /**
     * Log object with label to the console
     *
     * @param mixes $object
     * @param string $label
     * @return true
     * @throws Exception
     */
    public static function info($object, $label = null)
    {
        return self::send($object, $label, TempusDebugger::INFO);
    }

    /**
     * Log object with label to the console
     *
     * @param mixes $object
     * @param string $label
     * @return true
     * @throws Exception
     */
    public static function warn($object, $label = null)
    {
        return self::send($object, $label, TempusDebugger::WARN);
    }

    /**
     * Log object with label to the console
     *
     * @param mixes $object
     * @param string $label
     * @return true
     * @throws Exception
     */
    public static function error($object, $label = null)
    {
        return self::send($object, $label, TempusDebugger::ERROR);
    }

    /**
     * Dumps key and variable to console
     *
     * @param string $key
     * @param mixed $variable
     * @return true
     * @throws Exception
     */
    public static function dump($key, $variable)
    {
        return self::send($variable, $key, TempusDebugger::DUMP);
    }

    /**
     * Log a trace in the console
     *
     * @param string $label
     * @return true
     * @throws Exception
     */
    public static function trace($label)
    {
        return self::send($label, TempusDebugger::TRACE);
    }

    /**
     * Log a table in the console
     *
     * @param string $label
     * @param string $table
     * @return true
     * @throws Exception
     */
    public static function table($label, $table)
    {
        return self::send($table, $label, TempusDebugger::TABLE);
    }
}
