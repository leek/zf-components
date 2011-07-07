<?php
/**
 * Martin Hujer's Components
 *
 *   NOTE: Renamed to be part of the Leek_ namespace.
 *         See http://code.google.com/p/zfdev for original.
 *
 * @category   Mhujer
 * @package    Mhujer_View
 * @subpackage Helper
 * @author     Martin Hujer <mhujer@gmail.com>
 * @author     Chris Jones <leeked@gmail.com>
 * @see        http://code.google.com/p/zfdev/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Email.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * View helper to obfuscate an e-mail address
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Mhujer
 * @package    Mhujer_View
 * @subpackage Helper
 * @author     Martin Hujer <mhujer@gmail.com>
 * @author     Chris Jones <leeked@gmail.com>
 * @see        http://code.google.com/p/zfdev/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_Email extends Zend_View_Helper_Abstract
{
    /**
     * Obfuscates an e-mail address
     *
     * @param string $address E-mail address to obfuscate
     * @param boolean $mailto Generate mailto link?
     * @return string
     */
    public function email($address, $mailto = false)
    {
        $obfuscated = str_replace('@', '&#64;<!---->', $address);
        if (!$mailto) {
            return $obfuscated;
        } else {
            $mailtoAdress = str_replace('@', '&#64;', $address);
            $mailto = '<a href="mailto:' . $mailtoAdress . '">' . $obfuscated . '</a>';
            return $mailto;
        }
    }
}
