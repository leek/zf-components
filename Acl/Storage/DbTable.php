<?php

class Leek_Acl_Storage_DbTable extends Leek_Acl_Storage_Abstract implements Leek_Acl_Storage_Interface
{
    /**
     * Role table class
     *
     * @var Leek_Acl_Storage_DbTable_Roles
     */
    protected $_rolesDbTable = 'Leek_Acl_Storage_DbTable_Roles';

    /**
     * Resources table class
     *
     * @var Leek_Acl_Storage_DbTable_Resources
     */
    protected $_resourcesDbTable = 'Leek_Acl_Storage_DbTable_Resources';

    /**
     * Privileges table class
     *
     * @var Leek_Acl_Storage_DbTable_Privileges
     */
    protected $_privilegesDbTable = 'Leek_Acl_Storage_DbTable_Privileges';

    /**
     * Returns a rowset of privileges by a resource
     *
     * @param Leek_Acl_Resource $resource
     * @param Leek_Acl_Role $role
     * @return Zend_Db_Table_Rowset
     */
    public function getPrivilegesByResource($resource, $role = null)
    {
        return $this->getPrivilegesDbTable()->getPrivilegesByResource($resource, $role);
    }

    /**
     * Returns a REQUEST resource by name.
     *
     * @param string $resourceName
     * @param string $type
     * @return Leek_Acl_Resource
     */
    public function getResource($resourceName, $type = null)
    {
        if (isset($this->_resources[$resourceName])) {
            return $this->_resources[$resourceName];
        }

        switch (strtoupper($type)) {

            case Leek_Acl::RESOURCE_TYPE_REQUEST:
                $resource = $this->getResourcesDbTable()->getResourceByName($resourceName, Leek_Acl::RESOURCE_TYPE_REQUEST);
                break;

            default:
                return false;
                break;

        }

        $this->_resources[$resourceName] = $resource;
        return $resource;
    }

    /**
     * Returns the role marked "default".
     *
     * @return Leek_Acl_Role
     */
    public function getDefaultRole()
    {
        return $this->getRolesDbTable()->getDefaultRole();
    }

    /**
     * Sets the Roles DbTable class.
     *
     * @param mixed $rolesTable
     * @return Leek_Acl_Storage_DbTable
     */
    public function setRolesDbTable($rolesTable)
    {
        $this->_rolesDbTable = $rolesTable;
        return $this;
    }

    /**
     * Returns the Roles DbTable instance.
     *
     * @return Leek_Acl_Storage_DbTable_Roles
     */
    public function getRolesDbTable()
    {
        if ($this->_rolesDbTable instanceOf Zend_Db_Table_Abstract) {
            return $this->_rolesDbTable;
        }

        if (is_string($this->_rolesDbTable)) {
            $this->_rolesDbTable = new $this->_rolesDbTable();
        }

        return $this->_rolesDbTable;
    }

    public function setResourcesDbTable($resourcesTable)
    {
        $this->_resourcesDbTable = $resourcesTable;
        return $this;
    }

    public function getResourcesDbTable()
    {
        if ($this->_resourcesDbTable instanceOf Zend_Db_Table_Abstract) {
            return $this->_resourcesDbTable;
        }

        if (is_string($this->_resourcesDbTable)) {
            $this->_resourcesDbTable = new $this->_resourcesDbTable();
        }

        return $this->_resourcesDbTable;
    }

    public function setPrivilegesDbTable($privilegesTable)
    {
        $this->_privilegesDbTable = $privilegesTable;
        return $this;
    }

    public function getPrivilegesDbTable()
    {
        if ($this->_privilegesDbTable instanceOf Zend_Db_Table_Abstract) {
            return $this->_privilegesDbTable;
        }

        if (is_string($this->_privilegesDbTable)) {
            $this->_privilegesDbTable = new $this->_privilegesDbTable();
        }

        return $this->_privilegesDbTable;
    }
}
