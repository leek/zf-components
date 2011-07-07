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
 * Strips all spaces
 *
 * @uses       Zend_Filter_Interface
 * @category   Leek
 * @package    Leek_Filter
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Filter_RemoveSpaces extends Leek_Filter_StringTrimPlus
{
    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return parent::filter($value, null);
    }
}
