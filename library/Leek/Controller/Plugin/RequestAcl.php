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
 * @version    $Id: Acl.php 80 2010-01-07 16:15:56Z leeked $
 */

/**
 * Adds any resources for the current request and validates
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Plugin
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Controller_Plugin_RequestAcl extends Zend_Controller_Plugin_Abstract
{
    /**
     * Called after Zend_Controller_Router exits.
     *
     * Called after Zend_Controller_Front exits from the router.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $acl = Leek_Acl::getInstance();

        $resourceName = $this->_getResourceNameFromRequest($request);
        if (!$acl->addResourceFromStorage($resourceName, Leek_Acl::RESOURCE_TYPE_REQUEST)) {
            // Try module+controller only
            parse_str($resourceName, $parts);
            unset($parts['action']);
            $resourceName = http_build_query($parts);
            if (!$acl->addResourceFromStorage($resourceName, Leek_Acl::RESOURCE_TYPE_REQUEST)) {
                // Okay, try module only
                parse_str($resourceName, $parts);
                unset($parts['controller']);
                $resourceName = http_build_query($parts);
                $acl->addResourceFromStorage($resourceName, Leek_Acl::RESOURCE_TYPE_REQUEST);
            }
        }

        if (!$acl->validate($resourceName)) {

            $redirector     = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
            $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

            if (Zend_Auth::getInstance()->hasIdentity()) {

                $options = $acl->getDenyRedirectOptions($resourceName);
                if (!empty($options['message'])) {
                    $flashMessenger->addMessage(array('message' => $options['message'], 'type' => $options['type']));
                }

                $redirector->gotoSimpleAndExit(
                    $options['action'],
                    $options['controller'],
                    $options['module']
                );

            } else {

                //$session = new Zend_Session_Namespace('AclRedirect');
                //$session->redirectTo = $this->getActionController()->getRequest()->getRequestUri();

                $options = $acl->getNoAuthRedirectOptions($resourceName);
                if (!empty($options['message'])) {
                    $flashMessenger->addMessage(array('message' => $options['message'], 'type' => $options['type']));
                }

                $redirector->gotoSimpleAndExit(
                    $options['action'],
                    $options['controller'],
                    $options['module']
                );

            }

        }
        
    }

    /**
     * Returns a string based off of a request.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return string
     */
    private function _getResourceNameFromRequest(Zend_Controller_Request_Abstract $request)
    {
        $resourceName  = 'module='      . $request->getModuleName();
        $resourceName .= '&controller=' . $request->getControllerName();
        $resourceName .= '&action='     . $request->getActionName();

        return $resourceName;
    }
}
