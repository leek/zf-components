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
 * @version    $Id: MinHeadLink.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * HeadLink Minifier View Helper
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_MinHeadLink extends Zend_View_Helper_HeadLink
{
    /**
     * Relative to DOC_ROOT
     */
    protected $_minUrl = '/min/';

    /**
     * @var string registry key
     */
    protected $_regKey = 'Leek_View_Helper_MinHeadLink';

    public function minHeadLink()
    {
        return $this;
    }

    /**
     * Render link elements as string
     *
     * @param  string|int $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $stylesheets = array();
        $conditional = array();
        foreach ($this as $item) {
            if ($item->type == 'text/css' && !$item->conditionalStylesheet) {
                $stylesheets[$item->media][] = $item->href;
            } elseif ($item->conditionalStylesheet) {
                $conditional[$item->conditionalStylesheet][] = $item->href;
            }
        }

        $items = array();

        foreach ($stylesheets as $media => $href) {
            $item = new stdClass();
            $item->rel = 'stylesheet';
            $item->type = 'text/css';
            $item->href = $this->getMinUrl() . '?f=' . implode(',', $href);
            $item->media = $media;
            $item->conditionalStylesheet = false;
            $items[] = $this->itemToString($item);
        }

        foreach ($conditional as $conditionalValue => $href) {
            $item = new stdClass();
            $item->rel = 'stylesheet';
            $item->type = 'text/css';
            $item->href = $this->getMinUrl() . '?f=' . implode(',', $href);
            $item->media = 'screen, projection';
            $item->conditionalStylesheet = $conditionalValue;
            $items[] = $this->itemToString($item);
        }

        return $indent . implode($this->_escape($this->getSeparator()) . $indent, $items);
    }

    protected function getMinUrl()
    {
        return $this->_minUrl;
    }
}
