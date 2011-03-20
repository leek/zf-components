<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Plugin
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: LayoutSwitch.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Switch layout files based on current module.
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Plugin
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Controller_Plugin_LayoutSwitch extends Zend_Controller_Plugin_Abstract
{
    /**
     * Defined by Zend_Controller_Plugin_Abstract
     *
     * @uses Zend_Controller_Action_HelperBroker
     * @return void
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $layout     = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');
        $layoutPath = $layout->getLayoutPath();
        $module     = $request->getModuleName();

        if (file_exists($layoutPath . DIRECTORY_SEPARATOR . $module . '.phtml')) {
            $layout->setLayout($module);
        }
    }
}
