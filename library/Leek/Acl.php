<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Acl
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Acl.php 80 2010-01-07 16:15:56Z leeked $
 */

/**
 * @category   Leek
 * @package    Leek_Acl
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Acl extends Zend_Acl
{
    const RULE_TYPE_DENY        = 'DENY';
    const RULE_TYPE_ALLOW       = 'ALLOW';
    const RESOURCE_TYPE_REQUEST = 'REQUEST';

    /**
     * Allowed rule types.
     *
     * @var array
     */
    protected $_ruleTypes = array(self::RULE_TYPE_ALLOW, self::RULE_TYPE_DENY);

    /**
     * Allowed resource types.
     *
     * @var array
     */
    protected $_resourceTypes = array(self::RESOURCE_TYPE_REQUEST);

    /**
     * Singleton instance
     *
     * @var Leek_Acl
     */
    protected static $_instance = null;

    /**
     * Persistent storage handler
     *
     * @var Leek_Acl_Storage_Interface
     */
    protected $_storage = null;

    /**
     * Default Role
     *
     * @var mixed
     */
    protected $_defaultRole;

    /**
     * Current Role
     *
     * @var mixed
     */
    protected $_currentRole;

    /**
     * Zend_Auth role field name
     *
     * @var string
     */
    protected $_authRoleField;

    /**
     * Allow anything that has no resource/role
     *
     * @var bool
     */
    protected $_allowAll = false;

    /**
     * Options for when a user gets denied access
     * 
     * @var array
     */
    protected $_denyRedirectDefaultOptions = array(
        'module'     => null,
        'controller' => null,
        'action'     => null,
        'message'    => null,
        'type'       => null,
    );

    /**
     * Options for when a user is not logged in
     *
     * @var array
     */
    protected $_noAuthRedirectDefaultOptions = array(
        'module'     => null,
        'controller' => null,
        'action'     => null,
        'message'    => null,
        'type'       => null,
    );

    /**
     * Sets our allow all setting.
     *
     * @param bool $value
     * @return Leek_Acl
     */
    public function setAllowAll($value)
    {
        $this->_allowAll = (bool) $value;
        return $this;
    }

    /**
     * Returns our allow all setting.
     *
     * @return bool
     */
    public function getAllowAll()
    {
        return $this->_allowAll;
    }

    /**
     * Sets a deny default option
     *
     * @param string $key
     * @param string $value
     * @return Leek_Acl
     */
    public function setDenyRedirectDefaultOption($key, $value)
    {
        if (array_key_exists($key, $this->_denyRedirectDefaultOptions)) {
            $this->_denyRedirectDefaultOptions[$key] = $value;
        }

        return $this;
    }

    /**
     * Sets a no auth default option
     *
     * @param string $key
     * @param string $value
     * @return Leek_Acl
     */
    public function setNoAuthRedirectDefaultOption($key, $value)
    {
        if (array_key_exists($key, $this->_noAuthRedirectDefaultOptions)) {
            $this->_noAuthRedirectDefaultOptions[$key] = $value;
        }

        return $this;
    }

    /**
     * Return deny options for a resource
     *
     * @param string $resourceName
     * @return array
     */
    public function getDenyRedirectOptions($resourceName = null)
    {
        return $this->_denyRedirectDefaultOptions; //return array_merge($this->_denyRedirectDefaultOptions, $options);
    }

    /**
     * Return no auth options for a resource
     *
     * @param string $resourceName
     * @return array
     */
    public function getNoAuthRedirectOptions($resourceName = null)
    {
        return $this->_noAuthRedirectDefaultOptions; //return array_merge($this->_denyRedirectDefaultOptions, $options);
    }

    /**
     * Validate a resource and role against the ACL.
     *
     * @param Leek_Acl_Resource|string $resource
     * @param Leek_Acl_Role|string $role
     * @return bool
     */
    public function validate($resource, $role = null)
    {
        // Determine role
        if ($role == null) {
            $role = $this->getCurrentRole();
        }

        // Determine resource
        if (is_string($resource)) {
            $resource = $this->getStorage()->getResource($resource);
        }

        if ($this->has($resource)) {
            $roles = !is_array($role) ? array($role) : $role;
            foreach ($roles as $role) {
                if ($this->hasRole($role) && $this->isAllowed($role, $resource)) {
                    // At least one of the user's roles is allowed, so bail early
                    return true;
                }
            }
        } else {
            // Resource doesn't exist, allow it anyways?
            if ($this->getAllowAll()) {
                return true;
            }
        }

        // Failed ACL
        return false;
    }

    /**
     * Gets the current User's Role
     *
     * @return mixed
     */
    public function getCurrentRole()
    {
        if ($this->_currentRole !== null) {
            return $this->_currentRole;
        }

        // Try to use Zend_Auth
        $roleField = $this->getAuthRoleField();

        if (!empty($roleField)) {
            // Use Zend_Auth
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                 $role = $auth->getIdentity()->$roleField;
            }
        }

        if (!isset($role) || empty($role)) {
            // Use default
            $role = $this->getDefaultRole();
        }

        $this->_currentRole = $role;
        return $role;
    }

    /**
     * Add a role and it's parents recursivly.
     *
     * @param Leek_Acl_Role $role
     * @return Leek_Acl
     */
    public function addRoleRecursive(Leek_Acl_Role $role)
    {
        if (!$this->hasRole($role)) {
            if ($role->getExtends()) {
                $this->addRoleRecursive($role->getExtends());
                $this->addRole($role, $role->getExtends());
            } else {
                $this->addRole($role);
            }
        }

        return $this;
    }

    /**
     * Adds the current role to the registry.
     *
     * @return Leek_Acl
     */
    public function addCurrentRoleFromStorage()
    {
        $currentRole = $this->getCurrentRole();
        if (!is_array($currentRole)) {
            $currentRole = array($currentRole);
        }

        foreach ($currentRole as $role) {
            $this->addRoleRecursive($role);
        }

        return $this;
    }

    /**
     * Add a resource and it's parents recursivly.
     *
     * @param Leek_Acl_Resource|bool $resource
     * @return Leek_Acl
     */
    public function addResourceRecursive($resource)
    {
        if (!$resource) {
            return $this;
        }

        if (!$this->has($resource)) {

            // Add Resource
            if ($resource->getExtends()) {
                $this->addResourceRecursive($resource->getExtends());
                $this->addResource($resource, $resource->getExtends());
            } else {
                $this->addResource($resource);
            }

            // Add Privileges
            $rules = $this->getStorage()->getPrivilegesByResource($resource, $this->getCurrentRole());
         
            if ($rules) {
                foreach ($rules as $rule) {
                    if (in_array(strtoupper($rule->rule), $this->_ruleTypes)) {
                        $type = strtoupper($rule->rule);
                        $this->$type($rule->role_id, $resource);
                    }
                }
            }
            
        }

        return $this;
    }

    /**
     * Adds a resource from storage adapter.
     *
     * @param string $resourceName
     * @param string $type
     * @return Leek_Acl
     */
    public function addResourceFromStorage($resourceName, $type = null)
    {
        switch (strtoupper($type)) {

            case Leek_Acl::RESOURCE_TYPE_REQUEST:
                $resource = $this->getStorage()->getResource($resourceName, Leek_Acl::RESOURCE_TYPE_REQUEST);
                if (!$resource) {
                    return false;
                }
                $this->addResourceRecursive($resource);
                break;

        }
        
        return $this;
    }

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * Returns an instance of Leek_Acl
     *
     * Singleton pattern implementation
     *
     * @return Leek_Acl Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Sets the options for our Acl
     *
     * @param array $params
     * @return Leek_Acl
     */
    public function setParams(array $params)
    {
        foreach ($params as $key => $value) {

            switch (strtolower($key)) {

                case 'authrolefield':
                    $this->setAuthRoleField($value);
                    break;

                case 'defaultrole':
                    $this->setDefaultRole($value);
                    break;

                case 'roles':
                    if ($this->getStorage() instanceOf Leek_Acl_Storage_DbTable) {
                        if (isset($value['class'])) {
                            $this->getStorage()->setRolesDbTable($value['class']);
                        }
                        if (isset($value['options'])) {
                            $this->getStorage()->getRolesDbTable()->setOptions($value['options']);
                        }
                    }
                    break;

                case 'resources':
                    if ($this->getStorage() instanceOf Leek_Acl_Storage_DbTable) {
                        if (isset($value['class'])) {
                            $this->getStorage()->setResourcesDbTable($value['class']);
                        }
                        if (isset($value['options'])) {
                            $this->getStorage()->getResourcesDbTable()->setOptions($value['options']);
                        }
                    }
                    break;

                case 'rules':
                    if ($this->getStorage() instanceOf Leek_Acl_Storage_DbTable) {
                        if (isset($value['class'])) {
                            $this->getStorage()->setPrivilegesDbTable($value['class']);
                        }
                        if (isset($value['options'])) {
                            $this->getStorage()->getPrivilegesDbTable()->setOptions($value['options']);
                        }
                    }
                    break;

            }
            
        }

        return $this;
    }

    /**
     * Sets the persistent storage handler
     *
     * @param  string|Leek_Acl_Storage_Interface $storage
     * @return Leek_Acl Provides a fluent interface
     */
    public function setStorage($storage)
    {
        if (is_string($storage)) {
           switch (strtolower($storage)) {
               case 'config':
                   $storage = new Leek_Acl_Storage_Config();
                   break;
               case 'dbtable':
                   $storage = new Leek_Acl_Storage_DbTable();
                   break;
               default:
                   // @todo
                   break;
           }
        }

        $this->_storage = $storage;
        return $this;
    }

    /**
     * Returns the persistent storage handler
     *
     * @return Leek_Acl_Storage_Interface
     */
    public function getStorage()
    {
        if (null === $this->_storage) {
            // @todo
            return false;
        }

        return $this->_storage;
    }

    /**
     * Set the default role name
     *
     * @return Leek_Acl
     */
    public function setDefaultRole($role)
    {
        $this->_defaultRole = $role;
        return $this;
    }

    /**
     * Return the default role name
     *
     * @return Leek_Acl_Role
     */
    public function getDefaultRole()
    {
        if ($this->_defaultRole == null) {
            $this->_defaultRole = $this->getStorage()->getDefaultRole();
        } elseif (is_string($this->_defaultRole) || is_integer($this->_defaultRole)) {
            $this->_defaultRole = $this->getStorage()->getRolesDbTable()->getRoleById($this->_defaultRole);
        }

        return $this->_defaultRole;
    }

    /**
     * Set the Zend_Auth::getIdentity() role field name
     *
     * @return Leek_Acl
     */
    public function setAuthRoleField($fieldName)
    {
        $this->_authRoleField = (string) $fieldName;
        return $this;
    }

    /**
     * Return the Zend_Auth::getIdentity() role field name
     *
     * @return string
     */
    public function getAuthRoleField()
    {
        return $this->_authRoleField;
    }

}
