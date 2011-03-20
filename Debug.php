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
        if (extension_loaded('xdebug')) {
            return var_dump($thing);
        } else {
            if (class_exists('krumo', false)) {
                return krumo::dump($thing);
            } else {
                echo '<pre>';
                var_dump($thing);
                echo '</pre>';
            }
        }
    }
}