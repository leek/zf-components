<?php

class Leek_Acl_Storage_DbTable_Rowset_Resource extends Zend_Db_Table_Rowset_Abstract
{
    /**
     * Convert our DbTable rowset into an array of Resource instances.
     *
     * @return array
     */
    public function getResourceClasses()
    {
        $resources = array();

        foreach ($this as $row) {
            $resources[] = $row->getResourceClass();
        }

        return $resources;
    }
}