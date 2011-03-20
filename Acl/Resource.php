<?php

class Leek_Acl_Resource extends Zend_Acl_Resource
{
    /**
     * Type of resource.
     *
     * @var mixed
     */
    protected $_type;

    /**
     * Label for this resource.
     *
     * @var mixed
     */
    protected $_name;

    /**
     * Class instance for resource this one extends.
     *
     * @var Leek_Acl_Resource
     */
    protected $_extends;

    /**
     * Sets the Resource identifier
     *
     * @param string $id
     * @param array $options
     * @return void
     */
    public function __construct($resourceId, array $options = array())
    {
        $this->setOptions($options);
        return parent::__construct($resourceId);
    }

    /**
     * Sets the option for this role instance
     *
     * @param array $options
     * @return Leek_Acl_Resource
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {

            switch (strtolower($name)) {

                case 'id':
                    $this->_resourceId = $value;
                    break;

                case 'type':
                    $this->_type = $value;
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
     * Returns the type value for this resource.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Returns the name value for this resource.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Returns the extends name for this resource.
     * 
     * @return Leek_Acl_Resource|bool
     */
    public function getExtends()
    {
        if ($this->_extends instanceOf Leek_Acl_Resource) {
            return $this->_extends;
        }

        return false;
    }
}