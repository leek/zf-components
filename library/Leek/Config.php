<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Config
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Config.php 80 2010-01-07 16:15:56Z leeked $
 */

/**
 * Config helper class
 *
 * @category   Leek
 * @package    Leek_Config
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Config
{
    const DEFAULT_CACHE_MANAGER_KEY = 'config';
    const REGISTRY_PREFIX           = 'Leek_Config::';

    /**
    * Transforms input string by replacing parameters in the
    * template string with corresponding values
    *
    * @param string $subject	Template string
    * @param array $map		Key / value pairs to substitute with
    * @param string $delimiter	Template parameter delimiter (must be valid without escaping in a regular expression)
    * @param bool $blankIfNone  Set to blank if none found
    * @return string
    * @static
    */
    static public function templatize($subject, $map, $delimiter = '@', $blankIfNone = false)
    {
        if (preg_match_all('/' . $delimiter . '([a-z0-9_]+)' . $delimiter . '/', $subject, $matches)) {
            if ($matches[1]) {
                $map = array_change_key_case($map, CASE_LOWER);
                foreach ($matches[1] as $match) {
                    if (isset($map[$match])) {
                        $subject = str_replace($delimiter . $match . $delimiter, $map[$match], $subject);
                    } else {
                        if ($blankIfNone) {
                            $subject = str_replace($delimiter . $match . $delimiter, '', $subject);
                        }
                    }
                }
            }
        }

        return $subject;
    }

    static public function templatizeArray(array $subject, $map, $delimiter = '@', $blankIfNone = false)
    {
        foreach ($subject as $key => &$value) {
            $value = self::templatize($value, $map, $delimiter, $blankIfNone);
        }

        return $subject;
    }

    /**
     * Get a config out of the registry.
     *
     * @param string $key
     * @return Zend_Config
     * @static
     */
    static public function getRegistryConfig($key)
    {
        if (!Zend_Registry::isRegistered(self::REGISTRY_PREFIX . $key)) {
            return false;
        }

        return Zend_Registry::get(self::REGISTRY_PREFIX . $key);
    }

    /**
     * Stores a config in the registry.
     *
     * @param string $key
     * @param Zend_Config $config
     * @return Zend_Config
     * @static
     */
    static public function setRegistryConfig($key, $config)
    {
        return Zend_Registry::set(self::REGISTRY_PREFIX . $key, $config);
    }

    /**
     * Get a config out of the config bootstrap.
     *
     * @param string $key
     * @return Zend_Config
     * @static
     */
    static public function getBootstrapConfig($key)
    {
        if ($config = self::getRegistryConfig($key)) {
            return $config;
        }

        $frontController = Zend_Controller_Front::getInstance();
        $config = $frontController->getParam('bootstrap')->getResource('config');
        $config = isset($config[$key]) ? $config[$key] : false;

        if ($config) {
            if ($config['cacheKey']) {
                // Use Cache and Load
                $manager  = $frontController->getParam('bootstrap')->getResource('cachemanager');
                $cache    = $manager->getCache($config['cacheManagerKey']);
                $cacheKey = md5(APPLICATION_ENVIRONMENT . $config['path'] . filemtime($config['path']));
                
                if (!$configResult = $cache->load($cacheKey)) {
                    $configResult = self::loadConfig($config['path'], $config['environment']);
                    $cache->save($configResult, $cacheKey);
                }

                $config = $configResult;

            } else {
                // Load
                $config = self::loadConfig($config['path'], $config['environment']);
            }

            self::setRegistryConfig($key, $config);
            return $config;
        }

        return false;
    }

    /**
     * Load configuration file of options
     * Why isn't this function somewhere in ZF?
     *
     * @param  string $file
     * @param  string $environment Optional
     * @param  string $key Optional
     * @return array
     * @static
     */
    static public function loadConfig($file, $environment = null, $key = null)
    {
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        switch ($suffix) {
            case 'ini':
                if (!empty($environment)) {
                    $config = new Zend_Config_Ini($file, $environment);
                } else {
                    $config = new Zend_Config_Ini($file);
                }
                break;
            case 'xml':
                if (!empty($environment)) {
                    $config = new Zend_Config_Xml($file, $environment);
                } else {
                    $config = new Zend_Config_Xml($file);
                }
                break;
            case 'php':
            case 'inc':
                $config = include $file;
                if (isset($$key) && is_array($$key)) {
                    return $$key;
                }
                if (!is_array($config)) {
                    throw new Exception('Invalid configuration file provided; PHP file does not return array value');
                }
                return $config;
                break;

            default:
                throw new Exception('Invalid configuration file provided; unknown config type');
        }

        return $config->toArray();
    }
}
