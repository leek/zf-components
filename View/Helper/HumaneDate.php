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
 * @version    $Id: HumaneDate.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Generate more usable date
 *
 * @uses       Zend_View_Helper_Abstract
 * @category   Leek
 * @package    Leek_View
 * @subpackage Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_View_Helper_HumaneDate extends Zend_View_Helper_Abstract
{
    /**
     * Various time formats
     */
    private static $_time_formats = array(
        array(60, 'just now'),
        array(90, '1 minute'),                  // 60*1.5
        array(3600, 'minutes', 60),             // 60*60, 60
        array(5400, '1 hour'),                  // 60*60*1.5
        array(86400, 'hours', 3600),            // 60*60*24, 60*60
        array(129600, '1 day'),                 // 60*60*24*1.5
        array(604800, 'days', 86400),           // 60*60*24*7, 60*60*24
        array(907200, '1 week'),                // 60*60*24*7*1.5
        array(2628000, 'weeks', 604800),        // 60*60*24*(365/12), 60*60*24*7
        array(3942000, '1 month'),              // 60*60*24*(365/12)*1.5
        array(31536000, 'months', 2628000),     // 60*60*24*365, 60*60*24*(365/12)
        array(47304000, '1 year'),              // 60*60*24*365*1.5
        array(3153600000, 'years', 31536000),   // 60*60*24*365*100, 60*60*24*365
    );

    /**
     * Return a usable date
     *
     * @param string|Zend_Date $date_from
     * @param string|Zend_Date $date_to
     * @return string
     */
    public function humaneDate($date_from, $date_to = null)
    {
        // Get our TO date object
        if ($date_to === null) {
            $date_to = new Zend_Date(null, Zend_Date::ISO_8601);
        } else {
            if (!($date_to instanceof Zend_Date)) {
                $date_to = new Zend_Date($date_to, Zend_Date::ISO_8601);
            }
        }

        // Get our FROM date object
        if (!($date_from instanceof Zend_Date)) {
            $date_from = new Zend_Date($date_from, Zend_Date::ISO_8601);
        }

        $dateTo     = $date_to->getTimestamp();
        $dateFrom   = $date_from->getTimestamp();
        $difference = $dateTo - $dateFrom;
        $message    = '';
        $old        = false;

        if ($dateFrom <= 0) {

            return $message = 'A long time ago';

        } else {

            foreach (self::$_time_formats as $format) {

                if ($difference < $format[0]) {

                    // Old flag indicates older than 1 day, no need to autoupdate
                    if ($difference >= 86400) {
                        $old = true;
                    }

                    if (count($format) == 2) {
                        $message = $format[1] . ($format[0] === 60 ? '' : ' ago');
                        break;
                    } else {
                        $message = ceil($difference / $format[2]) . ' ' . $format[1] . ' ago';
                        break;
                    }

                }
            }

        }

        return sprintf('<abbr title="%sZ"%s>%s</abbr>',
            $date_from->get('YYYY-MM-ddTHH:mm:ss'),
            ($old ? ' class="old"' : ''),
            $message
        );
    }
}
