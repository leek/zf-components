<?php

class Leek_Acl_Storage_DbTable_Row_Role extends Zend_Db_Table_Row_Abstract
{
    /**
     * Convert our DbTable row into a Role instance.
     *
     * @return Leek_Acl_Role
     */
    public function getRoleClass()
    {
        $roleOptions = array(
            'id'      => $this->{$this->getTable()->getIdColumn()},
            'name'    => $this->{$this->getTable()->getLabelColumn()},
            'extends' => $this->{$this->getTable()->getExtendsColumn()},
        );

        if (!empty($roleOptions['extends'])) {
            $roleOptions['extends'] = $this->getTable()->getRoleById($roleOptions['extends']);
        }

        return new Leek_Acl_Role($roleOptions['id'], $roleOptions);
    }
}