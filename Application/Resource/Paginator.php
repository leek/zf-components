<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Application
 * @subpackage Resource
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Paginator.php 17 2009-05-26 15:45:24Z leeked $
 */

/**
 * Resource for setting defaults for paginator
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   Leek
 * @package    Leek_Application
 * @subpackage Resource
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Application_Resource_Paginator extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Set the default scrolling style for the Paginator
     *
     * @uses   Zend_Paginator
     * @param  string $scrollingStyle
     * @return Leek_Application_Resource_Paginator
     */
    public function setDefaultScrollingStyle($scrollingStyle)
    {
        Zend_Paginator::setDefaultScrollingStyle($scrollingStyle);
        return $this;
    }

    /**
     * Set the default view partial for the Paginator
     *
     * @uses   Zend_View_Helper_PaginationControl
     * @param  string|array $viewPartial
     * @return Leek_Application_Resource_Paginator
     */
    public function setDefaultViewPartial($viewPartial)
    {
        Zend_View_Helper_PaginationControl::setDefaultViewPartial($viewPartial);
        return $this;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
    }
}
