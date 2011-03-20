<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Form
 * @subpackage Element
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: SelectCountry.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Leek_Form
 *
 * @category   Leek
 * @package    Leek_Form
 * @subpackage Element
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Form_Element_SelectState extends Zend_Form_Element_Select
{
    /**
     * Default value
     *
     * @var null|string
     */
    protected $_defaultValue = null;

    /**
     * Default display
     *
     * @var null|string
     */
    protected $_defaultDisplay = null;

    /**
     * Initialize the state select box
     *
     * @return void
     */
    public function init()
    {
        $this->addMultiOption('', $this->_defaultDisplay);

        foreach (Leek_Address_US_States::getStates() as $abbr => $name) {
            $this->addMultiOption($abbr, ($abbr . ' - ' .$name));
        }

        if (!empty($this->_defaultValue)) {
            $this->setValue($this->_defaultValue);
        }
    }

    /**
     * Set the default value for the select
     *
     * @param mixed $value
     * @return Leek_Form_Element_SelectState
     */
    public function setDefaultValue($value)
    {
        $this->_defaultValue   = $value;
        return $this;
    }

    /**
     * Set the default value for the select
     *
     * @param mixed $value
     * @return Leek_Form_Element_SelectState
     */
    public function setDefaultDisplay($display)
    {
        $this->_defaultDisplay = $display;
        return $this;
    }
}
