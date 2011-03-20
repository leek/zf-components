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
 * @version    $Id: RouteUrl.php 80 2010-01-07 16:15:56Z leeked $
 */

/**
 * Uses the custom router to generate a URL
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_RouteUrl extends Zend_View_Helper_Abstract
{
    /**
     * @uses Zend_Controller_Front
     * @param string $routeFileName
     * @param string $routeName
     * @param array $urlOptions
     */
    public function routeUrl($routeFileName, $routeName, array $urlOptions = array(), $reset = true, $encode = true)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();

        if (!@$router->hasRoute($routeName)) {

            $module = null;

            // Pull the module name
            if (strpos($routeFileName, '/')) {

                $module        = substr($routeFileName, 0, strpos($routeFileName, '/'));
                $routeFileName = str_replace($module, '', $routeFileName);
                $routeFileName = ltrim($routeFileName, '/');

            }

            $router->addRoutesFromFile($routeFileName, $module);

        }

        return $this->view->url($urlOptions, $routeName, $reset, $encode);
    }
}
