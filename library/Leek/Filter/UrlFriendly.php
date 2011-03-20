<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Filter
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: UrlFriendly.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Filter that strips a string to be URL friendly
 *
 * @uses       Zend_Filter_Interface
 * @category   Leek
 * @package    Leek_Filter
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Filter_UrlFriendly implements Zend_Filter_Interface
{
    /**
     * @param string $value
     * @param string $space
     * @return string
     */
    public function filter($value, $space = '-')
    {
        $value = Zend_Filter::get($value, 'Alnum', array(true));
        $value = Zend_Filter::get($value, 'StringTrimPlus', array(), 'Leek_Filter');
        $value = str_replace(' ', $space, $value);
        return $value;
    }
}
