<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Application
 * @subpackage Resource
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Acl.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Resource for setting up Zend_Acl
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   Leek
 * @package    Leek_Application
 * @subpackage Resource
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Application_Resource_Acl extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    /**
     * @var Leek_Acl
     */
    protected $_acl;

    /**
     * Sets the deny default options
     *
     * @param array $options
     * @return Leek_Application_Resource_Acl
     */
    public function setDenyRedirectDefaults(array $options)
    {
        foreach ($options as $key => $value) {
            $this->getAcl()->setDenyRedirectDefaultOption($key, $value);
        }

        return $this;
    }

    /**
     * Sets the no auth default options
     *
     * @param array $options
     * @return Leek_Application_Resource_Acl
     */
    public function setNoAuthRedirectDefaults(array $options)
    {
        foreach ($options as $key => $value) {
            $this->getAcl()->setNoAuthRedirectDefaultOption($key, $value);
        }

        return $this;
    }

    /**
     * Sets the default role
     *
     * @param mixed $value
     * @return Leek_Application_Resource_Acl
     */
    public function setDefaultRole($value)
    {
        $this->getAcl()->setDefaultRole($value);
        return $this;
    }

    /**
     * Sets the auth role field name
     *
     * @param mixed $value
     * @return Leek_Application_Resource_Acl
     */
    public function setAuthRoleField($value)
    {
        $this->getAcl()->setAuthRoleField($value);
        return $this;
    }

    /**
     * Sets whether we should have a default whitelist
     * that allows all to everything
     *
     * @param bool $value
     * @return Leek_Application_Resource_Acl
     */
    public function setAllowAll($value)
    {
        if ((bool) $value) {
            $this->getAcl()->allow();
            $this->getAcl()->setAllowAll($value);
        }

        return $this;
    }

    /**
     * Defined by Zend_Application_Resource_ResourceAbstract
     *
     * @return void
     */
    public function init()
    {
        $this->getBootstrap()->bootstrap('Frontcontroller');

        // Make sure we bootstrap our modules first (if applicable)
        if ($this->getBootstrap()->hasPluginResource('Modules')) {
            $this->getBootstrap()->bootstrap('Modules');
        }

        foreach ($this->getOptions() as $key => $value) {

            switch (strtolower($key)) {

                case 'storage':
                    if (strtolower($value) == 'dbtable') {
                        if ($this->getBootstrap()->hasPluginResource('Db')) {
                            $this->getBootstrap()->bootstrap('Db');
                        }
                    }
                    $this->getAcl()->setStorage($value);
                    break;

                case 'params':
                    $this->getAcl()->setParams($value);
                    break;

            }

        }

        $this->getAcl()->addCurrentRoleFromStorage();
    }

    /**
     * Retrieve ACL instance
     *
     * @return Leek_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $this->_acl = Leek_Acl::getInstance();
        }
        return $this->_acl;
    }

    /**
     * Retrieve front controller instance
     *
     * @return Zend_Controller_Front
     */
    public function getFrontController()
    {
        if (null === $this->_front) {
            $this->_front = Zend_Controller_Front::getInstance();
        }
        return $this->_front;
    }
}
