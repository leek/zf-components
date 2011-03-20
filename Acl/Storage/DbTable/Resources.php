<?php

class Leek_Acl_Storage_DbTable_Resources extends Zend_Db_Table_Abstract
{
    const ID_COLUMN         = 'id';
    const LABEL_COLUMN      = 'name';
    const TYPE_COLUMN       = 'type';
    const ISDEFAULT_COLUMN  = 'is_default';
    const EXTENDS_COLUMN    = 'extends';

    protected $_name            = 'acl_resources';
    protected $_idColumn        = self::ID_COLUMN;
    protected $_labelColumn     = self::LABEL_COLUMN;
    protected $_typeColumn      = self::TYPE_COLUMN;
    protected $_isDefaultColumn = self::ISDEFAULT_COLUMN;
    protected $_extendsColumn   = self::EXTENDS_COLUMN;
    protected $_rowClass        = 'Leek_Acl_Storage_DbTable_Row_Resource';
    protected $_rowsetClass     = 'Leek_Acl_Storage_DbTable_Rowset_Resource';

    /**
     * Returns a resource record by ID.
     *
     * @param int $id
     * @return Leek_Acl_Resource|bool
     */
    public function getResourceById($id)
    {
        $select = $this->select();
        $select->where(sprintf('%s = %d', $this->_idColumn, (int) $id));
        $resource = $this->fetchRow($select);

        if ($resource) {
            return $resource->getResourceClass();
        }

        return false;
    }

    /**
     * Returns a resource record by name and/or type.
     *
     * @param string $name
     * @param string $type
     * @return Leek_Acl_Resource|bool
     */
    public function getResourceByName($name, $type = null)
    {
        $select = $this->select();
        $select->where(sprintf('%s = ?', $this->_labelColumn), $name);
        if ($type !== null) {
            $type = strtoupper($type);
            $select->where(sprintf('%s = ?', $this->_typeColumn), $type);
        }
        $resource = $this->fetchRow($select);

        if ($resource) {
            return $resource->getResourceClass();
        }
        
        return false;
    }

    /**
     * Returns only parent resources.
     *
     * @return Zend_Db_Table_Rowset_Abstract|bool
     */
    public function getParentResources()
    {
        $select = $this->select();
        $select->where(sprintf('%s IS NULL', $this->_extendsColumn));
        $resources = $this->fetchAll($select);

        if ($resources) {
            return $resources;
        }

        return false;
    }

    /**
     * Returns the children resources for a parent.
     *
     * @param Zend_Db_Table_Row_Abstract|int $resource
     * @return Zend_Db_Table_Rowset_Abstract|bool
     */
    public function getChildResources($resource)
    {
        $select = $this->select();

        if ($resource instanceOf Zend_Db_Table_Row_Abstract) {
            $idColumn = $this->_idColumn;
            $select->where(sprintf('%s = ?', $this->_extendsColumn), $resource->$idColumn);
        } elseif (is_integer($resource)) {
            $select->where(sprintf('%s = ?', $this->_extendsColumn), $resource);
        }

        $resources = $this->fetchAll($select);

        if ($resources) {
            return $resources;
        }

        return false;
    }

    /**
     * Set the options for this table reference
     *
     * @param array $options
     * @return Leek_Acl_Storage_DbTable_Resources
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

                case 'typecolumn':
                    $this->_typeColumn = (string) $value;
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
     * Returns the type column.
     *
     * @return string
     */
    public function getTypeColumn()
    {
        return $this->_typeColumn;
    }

    /**
     * Returns the is default column.
     *
     * @return string
     */
    public function getIsDefaultColumn()
    {
        return $this->_isDefaultColumn;
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