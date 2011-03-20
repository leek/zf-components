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
 * @version    $Id: MinHeadScript.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * HeadScript Minifier View Helper
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_MinHeadScript extends Zend_View_Helper_HeadScript
{
    /**
     * Relative to DOC_ROOT
     */
    protected $_minUrl = '/min/';

    /**
     * @var string registry key
     */
    protected $_regKey = 'Leek_View_Helper_MinHeadScript';

    public function minHeadScript()
    {
        return $this;
    }

    /**
     * Render script elements as string
     *
     * @param  string|int $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $scripts = array();
        foreach ($this as $item) {
            if ($item->type == 'text/javascript') {
                $scripts[] = $item->attributes['src'];
            }
        }

        $items = array();
        if (!empty($scripts)) {
            $item = new stdClass();
            $item->type = 'text/javascript';
            $item->attributes['src'] = $this->getMinUrl() . '?f=' . implode(',', $scripts);
            $item->source = null;
            $items[] = $this->itemToString($item, $indent, null, null);
        }

        $return = implode($this->getSeparator(), $items);
        return $return;
    }

    protected function getMinUrl()
    {
        return $this->_minUrl;
    }
}
