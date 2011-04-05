<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: FlashMesseger.php 65 2009-08-17 20:22:19Z leeked $
 */

/**
 * Navigation View Helper
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_Navigation_Config extends Zend_View_Helper_Abstract
{
    public function navigation_config($name)
    {
        $config    = Leek_Config::getBootstrapConfig('navigation');
        $container = new Zend_Navigation($config[$name]);

        return $this->view->partial('navigation.phtml', 'default', array('name' => $name, 'container' => $container));
    }
}
