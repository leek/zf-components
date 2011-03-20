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
 * @version    $Id: Referer.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Store current and referrer in the session
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Plugin
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Controller_Plugin_Referer extends Zend_Controller_Plugin_Abstract
{
    /**
     * Defined by Zend_Controller_Plugin_Abstract
     *
     * @uses Zend_Session_Namespace
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        $session = new Zend_Session_Namespace();
        $session->referer = $session->current;
        $session->current = $this->_request->getRequestUri();
    }
}
