<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: HeadPaginator.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Paginator View Helper
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_HeadPaginator extends Zend_View_Helper_Abstract
{
    public function headPaginator()
    {
        if (isset($this->view->paginator) && $this->view->paginator instanceof Zend_Paginator) {

            $paginator = $this->view->paginator;

            if (isset($paginator->getPages()->prev)) {
                echo $this->view->headLink(array(
                    'rel' => 'prev',
                    'href' => $this->view->url(array('page' => $paginator->getPages()->prev)),
                ), 'APPEND') . "\n";
            }

            if (isset($paginator->getPages()->next)) {
                echo $this->view->headLink(array(
                    'rel' => 'next',
                    'href' => $this->view->url(array('page' => $paginator->getPages()->next)),
                ), 'APPEND') . "\n";
            }
        }
    }
}
