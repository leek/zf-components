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
 * @version    $Id: Pluralize.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * View Helper to pluralize a word
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
class Leek_View_Helper_Pluralize extends Leek_View_Helper_Inflect_Abstract
{
    /**
     * @param string $string
     * @param int    $count Returns $count + string in context
     * @return string
     */
    public function pluralize($string, $count = null, $showCount = false)
    {
        // Return count with string if passed
        if ($count !== null) {
            if ($count == 1) {
                return ($showCount ? '1 ' : '') . $string;
            } else {
                return ($showCount ? $count . ' ' : '') . self::pluralize($string);
            }
        }

        // Save some time in the case that singular and plural are the same
        if (in_array(strtolower($string), self::$uncountable)) {
            return $string;
        }

        // Check for irregular singular forms
        foreach (self::$irregular as $pattern => $result) {
            $pattern = '/' . $pattern . '$/i';
            if (preg_match($pattern, $string)) {
                return preg_replace($pattern, $result, $string);
            }
        }

        // Check for matches using regular expressions
        foreach (self::$plural as $pattern => $result) {
            if (preg_match($pattern, $string)) {
                return preg_replace($pattern, $result, $string);
            }
        }

        return $string;
    }
}
