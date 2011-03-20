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
class Leek_Form_Element_Hidden extends Zend_Form_Element_Hidden
{
    /**
     * Initialize the hidden element and remove all decorators
     *
     * @return void
     */
    public function init()
    {
        $this->setDecorators(array('ViewHelper'));
    }
}
