<?php

abstract class Leek_Multidb_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract
{
    /**
     * @var Leek_Multidb
     */
    protected $_multiDb;
    
    /**
     * Saves the properties to the database.
     *
     * This performs an intelligent insert/update, and reloads the
     * properties with fresh data from the table on success.
     *
     * @return mixed The primary key value(s), as an associative array if the
     *     key is compound, or a scalar if the key is single-column.
     */
    public function save()
    {
        $writer = $this->_getMultiDb()->getRandomWriteAdapter();
        $this->_getTable()->setAdapter($writer);
        return parent::save();
    }
    
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

