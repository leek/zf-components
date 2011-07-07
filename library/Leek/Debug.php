<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Debug
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id$
 */

/**
 * Beautify VAR_DUMP
 *
 * @category   Leek
 * @package    Leek_Debug
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Debug
{
    public static function dump($thing)
    {
        // In case we are dumping inside of these
        echo '</script></style><div style="border: 2px solid red; background-color: white; padding: 5px">';
        
        $backtrace = debug_backtrace();
        foreach ($backtrace as $trace) {
            if ($trace['file'] != __FILE__) {
                if ($trace['function'] == 'dd' || $trace['function'] == 'd') {
                    echo "<pre style=\"font-size: 85%\">Line <b>{$trace['line']}</b> of <b>{$trace['file']}</b>:</pre>";
                }
            }
        }

        if (extension_loaded('xdebug')) {
            var_dump($thing);
        } else {
            if (class_exists('krumo', false)) {
                krumo::dump($thing);
            } else {
                echo '<pre>';
                var_dump($thing);
                echo '</pre>';
            }
        }
        
        echo '</div><br>';
    }
}

if (function_exists('d') === FALSE) {
    /**
     * Dump whichever way we can.
     *
     * @param mixed $thing
     * @return string
     */
    function d($thing) {
        Leek_Debug::dump($thing);
    }
}

if (function_exists('dd') === FALSE) {
    /**
     * Dump and die
     *
     * @see d()
     * @param mixed $thing
     */
    function dd($thing) {
        d($thing); exit;
    }
}