<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Router
 * @author     Federico Cargnelutti <fede.carg@gmail.com>
 * @author     Chris Jones <leeked@gmail.com>
 * @see        http://fedecarg.com/wiki/ZendController_Benchmarks
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Rewrite.php 45 2009-05-31 19:13:45Z leeked $
 */

/**
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Router
 * @author     Federico Cargnelutti <fede.carg@gmail.com>
 * @author     Chris Jones <leeked@gmail.com>
 * @see        http://fedecarg.com/wiki/ZendController_Benchmarks
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Controller_Router_Rewrite extends Zend_Controller_Router_Rewrite
{
    /**
     * Routes directories
     * @var array
     */
    protected $_routesDirectories = array();

    /**
     * Routes files type
     * @var string
     */
    protected $_routeFilesType = 'php';

    /**
     * Sets the current route files filetype
     *
     * @param string $type
     * @return Leek_Controller_Router_Rewrite
     */
    public function setRouteFilesType($type)
    {
        $this->_routeFilesType = $type;
        return $this;
    }

    /**
     * Returns the current route files filetype
     *
     * @return string
     */
    public function getRouteFilesType()
    {
        return $this->_routeFilesType;
    }

    /**
     * Adds a path to the stack of route directories
     *
     * @param string $path
     * @param string|null $key
     * @return Leek_Controller_Router_Rewrite
     */
    public function addRoutesDirectory($path, $key = null)
    {
        $path = rtrim((string) $path, '/\\');

        if ($key === null) {
            $this->_routesDirectories[] = $path;
        } else {
            $this->_routesDirectories[$key] = $path;
        }

        return $this;
    }

    /**
     * Returns all registered paths for route directories
     *
     * @return array
     */
    public function getRoutesDirectories()
    {
        return $this->_routesDirectories;
    }

    /**
     * Add routes from file
     *
     * @param string $controller
     * @param string|null $module
     * @return Leek_Controller_Router_Rewrite
     */
    public function addRoutesFromFile($controller, $module = null)
    {
        $routesDirectories = $this->getRoutesDirectories();
        $routeFile         = $controller . '.' . $this->getRouteFilesType();

        if ($module !== null) {

            // Only load from the specified module
            if (isset($routesDirectories[$module])) {
                $routesDirectories = array(
                    $module => $routesDirectories[$module]
                );
            }

        }

        foreach ($routesDirectories as $moduleName => $routeDirectory) {

            $file = $routeDirectory . DIRECTORY_SEPARATOR . $routeFile;

            // If the file doesn't exist, don't load any routes
            if (file_exists($file)) {
                $routes = Leek_Config::loadConfig($file, null, 'routes');
                $this->addRoutes($routes);
            }

        }

        return $this;
    }

}
