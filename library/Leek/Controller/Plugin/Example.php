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
 * @version    $Id: Example.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Example Controller plugin that outputs each function
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Plugin
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Controller_Plugin_Example extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        var_dump('routeStartup() called');
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        var_dump('routeShutdown() called');
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        var_dump('dispatchLoopStartup() called');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        var_dump('preDispatch() called');
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        var_dump('postDispatch() called');
    }

    public function dispatchLoopShutdown()
    {
        var_dump('dispatchLoopShutdown() called');
    }
}
