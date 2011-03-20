<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Form
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Form.php 83 2010-02-05 21:26:16Z leeked $
 */

/**
 * Leek_Form
 *
 * @category   Leek
 * @package    Leek_Form
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Form extends Zend_Filter_Input
{
    protected $_options = array();

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setOptions($this->_options);
        $this->setDisableTranslator(true);

        // Import any custom prefix paths
        $frontController = Zend_Controller_Front::getInstance();
        $frontOptions    = array_change_key_case($frontController->getParams(), CASE_LOWER);

        // Custom Filter paths
        if (isset($frontOptions['formfilterpaths']) && is_array($frontOptions['formfilterpaths'])) {
            foreach ($frontOptions['formfilterpaths'] as $filterPrefix => $filterPath) {
                $this->addFilterPrefixPath($filterPrefix, $filterPath);
            }
        }

        // Custom Validate paths
        if (isset($frontOptions['formvalidatepaths']) && is_array($frontOptions['formvalidatepaths'])) {
            foreach ($frontOptions['formvalidatepaths'] as $validatePrefix => $validatePath) {
                $this->addValidatorPrefixPath($validatePrefix, $validatePath);
            }
        }

        // Extensions...
        $this->init();
    }

    /**
     * Initialize form (used by extending classes)
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * @param array|string $data
     * @return boolean
     */
    public function isValid($data = null)
    {
        if (is_array($data)) {
            $this->setData($data);
            return parent::isValid();
        } else {
            return parent::isValid($data);
        }
    }

    /**
     * Return the valid fields
     *
     * @return array
     */
    public function getValues()
    {
        return array_merge($this->getEscaped(), $this->getUnknown());
    }

    /**
     * Override default behavior.
     * We don't want getMessages() to process.
     * 
     * @return array
     */
    public function getMessages()
    {
        if (!$this->_processed) {
            return false;
        }

        return parent::getMessages();
    }

    /**
     * Return the error message for a given field
     *
     * @param string $field
     * @return bool|string
     */
    public function getErrorMessage($field)
    {
        if (!$this->_processed) {
            return false;
        }

        // Check for errors
        $errors = $this->getErrors();
        if (!isset($errors[$field])) {
            return false;
        }

        // Retrieve the message
        $messages = $this->getMessages();
        if (is_array($errors[$field])) {
            foreach ($errors[$field] as $errorKey) {
                return isset($messages[$field][$errorKey]) ? $messages[$field][$errorKey] : false;
            }
        }

        return false;
    }

    public function getValue($field)
    {
        if (!$this->_processed) {
            if (isset($this->_validatorRules[$field]['default'])) {
                return $this->_validatorRules[$field]['default'];
            }
        }

        return $this->getEscaped($field);
    }
}
