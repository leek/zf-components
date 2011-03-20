<?php

class Leek_Acl_Storage_DbTable_Privileges extends Zend_Db_Table_Abstract
{
    const ID_COLUMN       = 'id';
    const RESOURCE_COLUMN = 'resource_id';
    const ROLE_COLUMN     = 'role_id';
    const TYPE_COLUMN     = 'type';

    protected $_name           = 'acl_privileges';
    protected $_idColumn       = self::ID_COLUMN;
    protected $_resourceColumn = self::RESOURCE_COLUMN;
    protected $_roleColumn     = self::ROLE_COLUMN;
    protected $_typeColumn     = self::TYPE_COLUMN;

    public function getPrivilegesByResource($resource, $role = null)
    {
        if ($resource instanceOf Leek_Acl_Resource) {
            $resource = $resource->getResourceId();
        }

        // Add role(s)
        $roles    = $role;
        $roleList = array();
        if (!is_array($roles)) {
            $roles = array($roles);
        }

        foreach ($roles as $role) {
            if ($role instanceOf Leek_Acl_Role) {
                $roleList[] = $role->getRoleId();
            } else {
                throw new Exception('$role isn\'t of type Leek_Acl_Role.');
            }
        }

        $select = $this->select();
        $select->where(sprintf('%s = %d', $this->_resourceColumn, $resource));
        if (count($roleList) > 1) {
            $select->where(sprintf('%s IN (?)', $this->_roleColumn), $roleList);
        } else {
            foreach ($roleList as $role) {
                $select->where(sprintf('%s = %d', $this->_roleColumn, $role));
                break;
            }
        }
        $privileges = $this->fetchAll($select);

        if ($privileges && $privileges->count() > 0) {
            return $privileges;
        }

        return false;
    }

    /**
     * Set the options for this table reference
     *
     * @param array $options
     * @return Leek_Acl_Storage_DbTable_Roles
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {

            switch (strtolower($key)) {

                case 'name':
                    if (strpos($value, '.')) {
                        list($this->_schema, $this->_name) = explode('.', $value);
                    } else {
                        $this->_name = (string) $value;
                    }
                    break;

                case 'idcolumn':
                    $this->_idColumn = (string) $value;
                    break;

                case 'resourcecolumn':
                    $this->_resourceColumn = (string) $value;
                    break;

                case 'rolecolumn':
                    $this->_roleColumn = (string) $value;
                    break;

                case 'typecolumn':
                    $this->typeColumn = (string) $value;
                    break;
                
            }

        }

        return $this;
    }
    
}