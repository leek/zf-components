<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Filter
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: StringTrimPlus.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Strips all double spaces into one single space
 *
 * @uses       Zend_Filter_Interface
 * @category   Leek
 * @package    Leek_Filter
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Filter_StringTrimPlus implements Zend_Filter_Interface
{
    /**
     * @param string $value
     * @param mixed $replace
     * @return string
     */
    public function filter($value, $replace = ' ')
    {
        $value = (string) $value;
        $value = trim(preg_replace('/\s+/', $replace, $value));
        return $value;
    }
}
