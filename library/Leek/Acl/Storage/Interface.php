<?php

interface Leek_Acl_Storage_Interface
{
    /**
     * Returns a rowset of privileges by a resource
     *
     * @param Leek_Acl_Resource $resource
     * @param Leek_Acl_Role $role
     * @return Zend_Db_Table_Rowset
     */
    public function getPrivilegesByResource($resource, $role = null);

    /**
     * Returns a REQUEST resource by name.
     *
     * @param string $resourceName
     * @param string $type
     * @return Leek_Acl_Resource
     */
    public function getResource($resourceName, $type = null);

    /**
     * Returns the role marked "default".
     *
     * @return Leek_Acl_Role
     */
    public function getDefaultRole();
}
