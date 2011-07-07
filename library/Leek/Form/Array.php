<?php

abstract class Leek_Form_Array
{
    /**
     * @var bool
     */
    protected $_hasErrors = false;
    
    /**
     * @var array[Leek_Form]
     */
    protected $_subforms = array();
    
    /**
     * @var array[string]
     */
    protected $_forms    = array();
    
    /**
     * @return void
     */
    public function __construct()
    {
        foreach ($this->_subforms as $subform) {
            $subform = strtolower($subform);
            $class   = get_class($this) . '_' . ucfirst($subform);
            if (class_exists($class)) {
                $this->_forms[$subform] = $class;
            }
        }
        
        if (!isset($this->_forms['default'])) {
            // Add default as catch-all
            $defaultClass = get_class($this) . '_Default';
            if (class_exists($defaultClass)) {
                $this->_forms['default'] = $defaultClass;
            }            
        }
    }
    
    /**
     * @param array $formData
     * @return bool
     */
    public function isValid(array $formData)
    {
        // Reset
        $this->_hasErrors = false;
        $defaults         = array();
        
        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                if ($subform = $this->getSubform($key)) {
                    if (!$subform->isValid($value)) {
                        $this->_hasErrors = true;
                    }
                }
            } else {
                $defaults[$key] = $value;
            }
        }
        
        // Default
        if ($defaultSubform = $this->getSubform('default')) {
            if (!$defaultSubform->isValid($defaults)) {
                $this->_hasErrors = true;
            }
        }
        
        return !$this->hasErrors();
    }
    
    /**
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function isFieldValid($field, $value)
    {
        $subform = 'default';
        if (strpos($field, '[') !== false && strpos($field, ']') !== false) {
            $parts   = explode('[', $field);
            $subform = strtolower($parts[0]);
            $field   = str_replace(']', null, $parts[1]);
        }
        
        if ($subform = $this->getSubform($subform)) {
            if (!$subform->isValid(array($field => $value))) {
                if ($error = $subform->getErrorMessage($field)) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * @param string $field
     * @return string
     */
    public function getFieldError($field)
    {
        $subform = 'default';
        if (strpos($field, '[') !== false && strpos($field, ']') !== false) {
            $parts   = explode('[', $field);
            $subform = strtolower($parts[0]);
            $field   = str_replace(']', null, $parts[1]);
        }
        
        if ($subform = $this->getSubform($subform)) {
            return $subform->getErrorMessage($field);
        }
        
        return false;
    }
    
    /**
     * @param string $key
     * @return Leek_Form|bool
     */
    public function getSubform($subform)
    {
        $subform = strtolower($subform);
        if (!isset($this->_forms[$subform])) {
            return false;
        }
        
        if (is_string($this->_forms[$subform])) {
            $className = $this->_forms[$subform];
            $this->_forms[$subform] = new $className();
        }

        return $this->_forms[$subform];
    }
    
    public function getValues()
    {
        $values = array();
        
        foreach ($this->_forms as $key => $subform) {
            /* @var $subform Leek_Form */
            if ($key == 'default') {
                $values = array_merge($values, $subform->getValues());
            } else {
                $values[$key] = $subform->getValues();
            }
        }
        
        return $values;
    }
    
    /**
     * @param string $key
     * @param string $subkey
     * @return mixed
     */
    public function getValue($key, $subkey = null)
    {
        if ($subkey == null) {
            $subkey = $key;
            $key    = 'default';
        }
        return $this->getSubform($key)->getValue($subkey);
    }
    
    /**
     * @param string $key
     * @param string $subkey
     * @return mixed
     */
    public function getError($key, $subkey = null)
    {
        if ($subkey == null) {
            $subkey = $key;
            $key    = 'default';
        }
        return $this->getSubform($key)->getErrorMessage($subkey);
    }
       
    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (bool) $this->_hasErrors;
    }
}