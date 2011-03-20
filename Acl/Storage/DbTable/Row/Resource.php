<?php

class Leek_Acl_Storage_DbTable_Row_Resource extends Zend_Db_Table_Row_Abstract
{
    private $_children;

    /**
     * Convert our DbTable row into a Resource instance.
     *
     * @return Leek_Acl_Resource
     */
    public function getResourceClass()
    {
        $resourceOptions = array(
            'id'      => $this->{$this->getTable()->getIdColumn()},
            'name'    => $this->{$this->getTable()->getLabelColumn()},
            'type'    => $this->{$this->getTable()->getTypeColumn()},
            'extends' => $this->{$this->getTable()->getExtendsColumn()},
        );

        if (!empty($resourceOptions['extends'])) {
            $resourceOptions['extends'] = $this->getTable()->getResourceById($resourceOptions['extends']);
        }

        return new Leek_Acl_Resource($resourceOptions['id'], $resourceOptions);
    }

    public function getChildResources()
    {
        if ($this->_children !== null) {
            return $this->_children;
        }

        $this->_children = $this->getTable()->getChildResources($this);
        return $this->_children;
    }
}