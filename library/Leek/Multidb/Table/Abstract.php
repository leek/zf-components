<?php

abstract class Leek_Multidb_Table_Abstract extends Zend_Db_Table_Abstract
{
    /**
     * @var Leek_Multidb
     */
    protected $_multiDb;
    
    /**
     * Returns an instance of a Zend_Db_Table_Select object.
     *
     * @param bool $withFromPart Whether or not to include the from part of the select based on the table
     * @return Zend_Db_Table_Select
     */
    public function select($withFromPart = self::SELECT_WITHOUT_FROM_PART)
    {   
        $reader = $this->_getMultiDb()->getRandomReadAdapter();
        $this->_setAdapter($reader);
        return parent::select($withFromPart);
    }
    
    /**
     * This only exists so we can swap the adapter from the outside.
     * 
     * @param  mixed $db Either an Adapter object, or a string naming a Registry key
     * @return Zend_Db_Table_Abstract Provides a fluent interface
     */
    public function setAdapter($db)
    {
        return $this->_setAdapter($db);
    }
    
    /**
     * Retrieve Front Controller instance
     *
     * @return Zend_Controller_Front
     */
    protected function _getMultiDb()
    {
        if (null === $this->_multiDb) {
            $this->_multiDb = Leek_Multidb::getInstance();
        }
        return $this->_multiDb;
    }
}

