<?php

class Leek_Acl_Role extends Zend_Acl_Role
{
    /**
     * Label for this role.
     *
     * @var mixed
     */
    protected $_name;

    /**
     * Class instance for role this one extends.
     *
     * @var Leek_Acl_Role
     */
    protected $_extends;

    /**
     * Sets the Role identifier
     *
     * @param string $id
     * @param array $options
     * @return void
     */
    public function __construct($roleId, array $options = array())
    {
        $this->setOptions($options);
        return parent::__construct($roleId);
    }

    /**
     * Sets the option for this role instance
     *
     * @param array $options
     * @return Leek_Acl_Role
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {

            switch (strtolower($name)) {

                case 'id':
                    $this->_roleId = $value;
                    break;

                case 'name':
                    $this->_name = $value;
                    break;

                case 'extends':
                    $this->_extends = $value;
                    break;

            }

        }

        return $this;
    }

    /**
     * Returns the name value for this role.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Returns the extends name for this role.
     * 
     * @return mixed
     */
    public function getExtends()
    {
        if ($this->_extends instanceOf Leek_Acl_Role) {
            return $this->_extends;
        }

        return false;
    }
}