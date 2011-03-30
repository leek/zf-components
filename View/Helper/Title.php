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
 * @version    $Id: Title.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Outputs the current page title
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_Title extends Zend_View_Helper_Abstract
{
    /**
     * Returns title
     *
     * @return string
     */
    public function title()
    {
        $headTitle = $this->view->headTitle()->toString();

        // Remove <title> Tag
        $headTitle = str_replace('<title>', '', $headTitle);
        $headTitle = str_replace('</title>', '', $headTitle);

        // Remove and prefixes/seperators
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $options   = $bootstrap->getOptions();
        if (isset($options['resources']['view']['headTitle'])) {
            $headTitleOptions = $options['resources']['view']['headTitle'];
            foreach ($headTitleOptions as $option => $value) {
                switch ($option) {
                    case 'title':
                    case 'seperator':
                        $headTitle = str_replace($value, '', $headTitle);
                        break;
                    default:
                        $headTitle = str_replace($option, '', $headTitle);
                        break;
                }
            }
        }

        return $headTitle;
    }
}
