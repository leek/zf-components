<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper_Inflect
 * @author     Sho Kuwamoto <sho@kuwamoto.org>
 * @author     Chris Jones <leeked@gmail.com>
 * @see        http://www.eval.ca/articles/php-pluralize (MIT license)
 * @see        http://dev.rubyonrails.org/browser/trunk/activesupport/lib/active_support/inflections.rb (MIT license)
 * @see        http://www.fortunecity.com/bally/durrus/153/gramch13.html
 * @see        http://www2.gsu.edu/~wwwesl/egw/crump.htm
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Singularize.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * View helper to singularize a word
 *
 * @uses       Leek_View_Helper_Inflect_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper_Inflect
 * @author     Sho Kuwamoto <sho@kuwamoto.org>
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_Singularize extends Leek_View_Helper_Inflect_Abstract
{
    /**
     * @param string $string
     * @return string
     */
    public static function singularize($string)
    {
        // Save some time in the case that singular and plural are the same
        if (in_array(strtolower($string), self::$uncountable)) {
            return $string;
        }

        // Check for irregular plural forms
        foreach (self::$irregular as $result => $pattern) {
            $pattern = '/' . $pattern . '$/i';
            if (preg_match($pattern, $string)) {
                return preg_replace($pattern, $result, $string);
            }
        }

        // Check for matches using regular expressions
        foreach (self::$singular as $pattern => $result) {
            if (preg_match($pattern, $string)) {
                return preg_replace($pattern, $result, $string);
            }
        }

        return $string;
    }
}
