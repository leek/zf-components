<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: HeadFavicon.php 72 2009-10-14 05:46:48Z leeked $
 */

/**
 * Favicon View Helper
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_HeadFavicon extends Zend_View_Helper_Abstract
{
    public function headFavicon($href, $type = 'image/x-icon')
    {
        return sprintf(
            '<link rel="shortcut icon" href="%s" type="%s">',
            $href,
            $type
        );
    }
}
