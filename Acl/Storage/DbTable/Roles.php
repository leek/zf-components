<?php

class Leek_Acl_Storage_DbTable_Roles extends Zend_Db_Table_Abstract
{
    const ID_COLUMN         = 'id';
    const LABEL_COLUMN      = 'name';
    const ISDEFAULT_COLUMN  = 'is_default';
    const EXTENDS_COLUMN    = 'extends';

    protected $_name            = 'acl_roles';
    protected $_idColumn        = self::ID_COLUMN;
    protected $_labelColumn     = self::LABEL_COLUMN;
    protected $_isDefaultColumn = self::ISDEFAULT_COLUMN;
    protected $_extendsColumn   = self::EXTENDS_COLUMN;

    /**
     * Returns a role record by ID.
     *
     * @param int $id
     * @return array|bool
     */
    public function getRoleById($id, $returnDbRow = false)
    {
        $select = $this->select();
        $select->where(sprintf('%s = %d', $this->_idColumn, (int) $id));
        $role = $this->fetchRow($select);

        if ($role) {
            if ($returnDbRow) {
                return $role;
            } else {
                return $role->getRoleClass();
            }
        } else {
            return false;
        }
    }

    /**
     * Returns the default role record.
     *
     * @return array|bool
     */
    public function getDefaultRole()
    {
        $select = $this->select();
        $select->where(sprintf('%s = 1', $this->_isDefaultColumn));
        $role = $this->fetchRow($select);

        if ($role) {
            return $this->_getRoleClass($role);
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

                case 'labelcolumn':
                    $this->_labelColumn = (string) $value;
                    break;

                case 'isdefaultcolumn':
                    $this->_isDefaultColumn = (string) $value;
                    break;

                case 'extendscolumn':
                    $this->_extendsColumn = (string) $value;
                    break;

                case 'dependent':
                    $this->_dependentTables = (array) $value;
                    break;
                
            }

        }

        return $this;
    }

    /**
     * Returns the ID column.
     *
     * @return string
     */
    public function getIdColumn()
    {
        return $this->_idColumn;
    }

    /**
     * Returns the label column.
     *
     * @return string
     */
    public function getLabelColumn()
    {
        return $this->_labelColumn;
    }

    /**
     * Returns the extends column.
     *
     * @return string
     */
    public function getExtendsColumn()
    {
        return $this->_extendsColumn;
    }
}