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
 * @version    $Id: Truncate.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Overly complex? truncation routine
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_Truncate extends Zend_View_Helper_Abstract
{
    /**
     * Truncates a string
     *
     * @param string $text
     * @param int $length Number of characters to truncate to
     * @param bool $exact Exactly $length?
     * @param bool $considerHtml Consider HTML tags in count?
     * @param string $ending What do add to the end
     * @return string
     */
    public function truncate($text, $length = 150, $exact = false, $considerHtml = false, $ending = '&hellip;')
    {
        if ($considerHtml) {

            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }

            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';

            foreach ($lines as $line_matchings) {
                if (!empty($line_matchings[1])) {
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    $truncate .= $line_matchings[1];
                }
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length > $length) {
                    $left = $length - $total_length;
                    $entities_length = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                if($total_length >= $length) {
                    break;
                }
            }

        } else {

            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }

        }

        if (!$exact) {
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                    $truncate = substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;

        if($considerHtml) {
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }
}
