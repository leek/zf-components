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
 * @version    $Id: View.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Resource (extension) for adding additional options to Zend_View
 *
 * @uses       Zend_Application_Resource_View
 * @category   Leek
 * @package    Leek_Application
 * @subpackage Resource
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Application_Resource_View extends Zend_Application_Resource_View
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $view    = $this->getView();
        $options = array_change_key_case($this->getOptions(), CASE_LOWER);

        foreach ($options as $key => $value) {
            switch (strtolower($key)) {

                //
                // Setup <TITLE>
                //
                case 'headtitle':
                    foreach ($value as $headTitleKey => $headTitleValue) {
                        if ($headTitleKey == 'seperator') {
                            $view->headTitle()->setSeparator($headTitleValue);
                        } else {
                            switch (strtolower($headTitleValue)) {
                                case 'prepend':
                                case 'append':
                                    $view->headTitle($headTitleKey, strtoupper($headTitleValue));
                                    break;

                                default:
                                    $view->headTitle($headTitleValue);
                                    break;
                            }
                        }
                    }
                    break;

                //
                // Setup <META>
                //
                case 'headmeta':
                    foreach ($value as $headMetaKey => $headMetaValue) {
                        switch (strtolower($headMetaKey)) {
                            case 'httpequiv':
                            case 'name':
                                $appendFunction = 'append' . ucfirst($headMetaKey);
                                if (is_array($headMetaValue)) {
                                    foreach ($headMetaValue as $contentKey => $contentValue) {
                                        $view->headMeta()->$appendFunction($contentKey, $contentValue);
                                    }
                                }
                                break;
                        }
                    }
                    break;
            }
        }
    }
}
