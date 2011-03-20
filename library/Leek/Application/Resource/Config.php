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
 * @version    $Id: Cache.php 55 2009-06-09 02:20:45Z leeked $
 */

/**
 * Resource for setting up one or many configs using Zend_Config
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   Leek
 * @package    Leek_Application
 * @subpackage Resource
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Application_Resource_Config extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var array
     */
    protected $_configs;

    /**
     * Sets the cache array.
     * This function only stores the paths so we can lazy
     * load the configs when we need them.
     *
     * @param string $key
     * @param string $filePath
     * @return Leek_Application_Resource_Config
     */
    public function addConfig($key, $filePath)
    {
        if (is_array($filePath)) {

            $options = array_change_key_case($filePath, CASE_LOWER);

            if (isset($options['path'])) {

                $environment = isset($options['useenvironment']) && $options['useenvironment'] ? $this->getBootstrap()->getEnvironment() : null;
                $cache       = isset($options['useconfigcache']) ? (bool) $options['useconfigcache'] : false;
                $cacheKey    = isset($options['configcachekey']) && !empty($options['configcachekey']) ? $options['configcachekey'] : 'config';

                $this->_configs[$key] = array(
                    'path'        => $options['path'],
                    'environment' => $environment,
                    'cache'       => $cache,
                    'cacheKey'    => $cacheKey,
                );

            }

        } else {

            $this->_configs[$key] = array(
                'path'        => $filePath,
                'environment' => null,
                'cache'       => false,
                'cacheKey'    => null,
            );

        }
       
        return $this;
    }

    /**
     * Returns the configs array
     *
     * @return array
     */
    public function getConfigs()
    {
        return $this->_configs;
    }

    /**
     * Defined by Zend_Application_Resource_ResourceAbstract
     *
     * @return array
     */
    public function init()
    {
        $options = $this->getOptions();

        foreach ($options as $key => $filePath) {
            $this->addConfig($key, $filePath);
        }

        return $this->getConfigs();
    }
}
