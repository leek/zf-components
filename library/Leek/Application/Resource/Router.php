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
 * @version    $Id: Router.php 61 2009-06-25 16:31:16Z leeked $
 */

/**
 * Resource (extension) for setting up our custom Rewrite Router
 *
 * @uses       Zend_Application_Resource_Router
 * @category   Leek
 * @package    Leek_Application
 * @subpackage Resource
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Application_Resource_Router extends Zend_Application_Resource_Router
{
    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    /**
     * @var Leek_Controller_Router_Rewrite
     */
    protected $_router;

    /**
     * @var Zend_Controller_Request
     */
    protected $_request;

    /**
     * Sets the routes directory
     *
     * @return Leek_Application_Resource_Router
     */
    public function setRoutesDirectory($routesDirectory)
    {
        if (is_string($routesDirectory)) {
            $this->getRouter()->addRoutesDirectory($routesDirectory, $this->getFrontController()->getDefaultModule());
        }
        return $this;
    }

    /**
     * Sets the routes files filetype
     *
     * @return Leek_Application_Resource_Router
     */
    public function setRouteFilesType($type)
    {
        $this->getRouter()->setRouteFilesType($type);
        return $this;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        $this->getBootstrap()->bootstrap('Frontcontroller');

        $router  = $this->getRouter();
        $options = $this->getOptions();

        foreach ($options as $key => $value) {
            switch (strtolower($key)) {

                case 'modules':
                    $modules       = $this->getFrontController()->getControllerDirectory();
                    $moduleOptions = array_change_key_case($value, CASE_LOWER);
                    foreach ($moduleOptions as $moduleKey => $moduleValue) {
                        switch ($moduleKey) {

                            // Suffix to add to module directory to get path
                            case 'directorysuffix':
                                foreach ($modules as $moduleName => $modulePath) {
                                    $moduleRoutePath = realpath($modulePath . '/../' . $moduleValue);
                                    if ($moduleRoutePath) {
                                        $router->addRoutesDirectory($moduleRoutePath, $moduleName);
                                    }
                                }
                                break;

                            // Load specified route file for each module (if exists)
                            case 'loadroutefiles':
                            case 'loadroutefile':
                                if (is_string($moduleValue)) {
                                    $router->addRoutesFromFile($moduleValue);
                                } elseif (is_array($moduleValue)) {
                                    foreach ($moduleValue as $routeFile) {
                                        $router->addRoutesFromFile($routeFile);
                                    }
                                }
                                break;

                        }
                    }
                    break;

                case 'loadroutefiles':
                case 'loadroutefile':
                    if (is_string($value)) {
                        $router->addRoutesFromFile($value);
                    } elseif (is_array($value)) {
                        foreach ($value as $routeFile) {
                            $router->addRoutesFromFile($routeFile);
                        }
                    }
                    break;
            }
        }

        // Attempt to load route for current request
        $request = $this->getRequest();
        $uri     = ltrim((string) $request->getRequestUri(), '/');

        // Attempt to pull module name (if applicable)
        if (strpos($uri, '/')) {

            $module     = substr($uri, 0, strpos($uri, '/'));
            $controller = str_replace($module, '', $uri);
            $controller = ltrim($controller, '/');

            if (strpos($controller, '/')) {
                $controller = substr($controller, 0, strpos($controller, '/'));
            }

            if (!empty($module)) {
                $modules = $this->getFrontController()->getControllerDirectory();
                if (isset($modules[$module])) {
                    $router->addRoutesFromFile($controller, $module);
                } else {
                    $router->addRoutesFromFile($module, $this->getFrontController()->getDefaultModule());
                }
            }

        } else {

            $router->addRoutesFromFile($uri);

        }

        $this->getFrontController()->setRouter($router);
    }

    /**
     * Retrieve request object
     *
     * @return Zend_Controller_Request
     */
    public function getRequest()
    {
        if (null === $this->_request) {
            $request = $this->getFrontController()->getRequest();
            if (null === $request) {
                // Instantiate default request object (HTTP version)
                $request = new Zend_Controller_Request_Http();
                $this->getFrontController()->setRequest($request);
            }
            $this->_request = $request;
        }
        return $this->_request;
    }

    /**
     * Retrieve custom router instance
     *
     * @return Leek_Controller_Router_Rewrite
     */
    public function getRouter()
    {
        if (null === $this->_router) {
            $this->_router = new Leek_Controller_Router_Rewrite();
        }
        return $this->_router;
    }

    /**
     * Retrieve front controller instance
     *
     * @return Zend_Controller_Front
     */
    public function getFrontController()
    {
        if (null === $this->_front) {
            $this->_front = Zend_Controller_Front::getInstance();
        }
        return $this->_front;
    }
}
