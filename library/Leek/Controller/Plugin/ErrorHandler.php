<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Plugin
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: Example.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Example Controller plugin that outputs each function
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Plugin
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Controller_Plugin_ErrorHandler extends Zend_Controller_Plugin_ErrorHandler
{
    /**
     * Module to use for errors; defaults to default module in dispatcher
     * @var string
     */
    protected $_errorModule = 'default';
}
