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
 * @version    $Id: HumaneTime.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Generate more usable time
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_HumaneTime extends Zend_View_Helper_Abstract
{
    /**
     * Return a usuable time
     *
     * @param int $seconds
     * @return string
     */
    public function humaneTime($seconds)
    {
        $seconds = (int) $seconds;

        if ($seconds <= 0) {
            return false;
        }

        $message = '';

        $days     = intval($seconds/86400);
        $seconds -= $days*86400;

        $hours    = intval($seconds/3600);
        $seconds -= $hours*3600;

        $minutes  = intval($seconds/60);
        $seconds -= $minutes*60;

        if ($days) {
            $message .= $days .    ($days > 1    ? ' days '    : ' day ');
        }

        if ($hours){
            $message .= $hours .   ($hours > 1   ? ' hours '   : ' hour ');
        }

        if ($minutes) {
            $message .= $minutes . ($minutes > 1 ? ' minutes ' : ' minute ');
        }

        if ($seconds) {
            $message .= $seconds . ($seconds > 1 ? ' seconds ' : ' second ');
        }

        return $message;
    }
}
