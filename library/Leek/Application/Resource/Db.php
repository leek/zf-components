<?php

class Leek_Application_Resource_Db extends Zend_Application_Resource_Db
{
    /**
     * Holds the default metadata cache
     *
     * @var string|null
     */
    protected $_defaultMetadataCache;

    /**
     * Sets the default metadata cache name.
     *
     * @param string $cacheName
     * @return Zend_Application_Resource_Db
     */
    public function setDefaultMetadataCache($cacheName)
    {
        $this->_defaultMetadataCache = $cacheName;
        return $this;
    }

    /**
     * Returns the default metadata cache name
     *
     * @return string
     */
    public function getDefaultMetadataCache()
    {
        return $this->_defaultMetadataCache;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_Db_Adapter_Abstract|null
     */
    public function init()
    {
        parent::init();

        // Make sure we bootstrap our Cache Manager first
        if ($this->getBootstrap()->hasPluginResource('Cachemanager')) {
            $this->getBootstrap()->bootstrap('Cachemanager');

            if ($cacheName = $this->getDefaultMetadataCache()) {
                $cacheManager = $this->getBootstrap()->getResource('cachemanager');
                if ($cacheManager->hasCache($cacheName)) {
                    $databaseCache = $cacheManager->getCache($cacheName);
                    Zend_Db_Table_Abstract::setDefaultMetadataCache($databaseCache);
                }
            }

        }
    }
}
