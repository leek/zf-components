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
 * @version    $Id: FlashMesseger.php 65 2009-08-17 20:22:19Z leeked $
 */

/**
 * FlashMessenger View Helper
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_FlashMesseger extends Zend_View_Helper_Abstract
{
    public function flashMesseger()
    {
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $flashMessages  = $flashMessenger->getMessages();

        if (empty($flashMessages)) {
            $flashMessages = $flashMessenger->getCurrentMessages();
        }

        if (!empty($flashMessages)) {
            $flashMessenger->clearCurrentMessages();
            return $this->view->partial('flashmessenger.phtml', 'default', array('flashMessages' => $flashMessages));
        }
    }
}
