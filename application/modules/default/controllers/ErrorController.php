<?php
/**
 * @author      Chris Jones <leeked@gmail.com>
 * @category    Default
 * @package     Default_Controller
 */

/**
 * @category    Default
 * @package     Default_Controller
 */
class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {

            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:

                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page Not Found';
                $this->view->code    = 404;
                $this->view->info    = sprintf(
                    'Unable to find controller "%s" in module "%s"',
                    $errors->request->getControllerName(),
                    $errors->request->getModuleName()
                );
                break;

            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page Not Found';
                $this->view->code    = 404;
                $this->view->info    = sprintf(
                    'Unable to find action "%s" in controller "%s" in module "%s"',
                    $errors->request->getActionName(),
                    $errors->request->getControllerName(),
                    $errors->request->getModuleName()
                );
                break;

            default:

                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message   = 'Application Error';
                $this->view->code      = 500;
                $this->view->info      = null;
                $this->view->exception = $errors->exception;

                // Pretty display
                $error = new Leek_Error($errors->exception);
                $this->view->lineFormatted  = $error->getFormattedLine();
                $this->view->traceFormatted = $error->getFormattedTrace();
                $this->view->traceText      = $error->getTextTrace();
                break;
        }

        
        $this->view->request = $errors->request;
    }

}