<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Validate
 * @author     Chris Jones <leeked@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id$
 */

/**
 * URL Validator
 *
 * @category   Leek
 * @package    Leek_Validate
 * @author     Chris Jones <leeked@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Validate_Url extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value is not a valid URL
     */
    const INVALID_URL = 'invalidUrl';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL   => "'%value%' is not a valid URL.",
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value only contains a valid URL
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        if (!Zend_Uri::check($value)) {
            $this->_error(self::INVALID_URL);
            return false;
        }

        return true;
    }
}
