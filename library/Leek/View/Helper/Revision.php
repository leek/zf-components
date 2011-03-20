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
 * @version    $Id$
 */

/**
 * Formats the current revision number from $Rev$
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_Revision extends Zend_View_Helper_Abstract
{
    /**
     * Returns revision number only
     *
     * @return int
     */
    public function revision($revision)
    {
        $revision = trim(str_replace('$', '', $revision));
        $revision = trim(str_replace('Revision: ', '', $revision));
        return (int) $revision;
    }
}
