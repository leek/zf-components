<?php
/**
 * Leek - Zend Framework Components
 *
 *   NOTE: Renamed to be part of the Leek_ namespace.
 *         See http://www.arietis-software.com/index.php/2009/05/26/a-php-calendar-class-based-on-zend_date
 *         for original.
 *
 * @category   Leek
 * @package    Leek_Calendar
 * @author     Derek Harnanansingh <code@arietis-software.com>
 * @author     Chris Jones <leeked@gmail.com>
 * @see        http://www.arietis-software.com/index.php/2009/05/26/a-php-calendar-class-based-on-zend_date
 * @see        http://arietis-software.com/sandbox/calendar_demo.php
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

/*
 * @requires   Zend_Date, Zend_Locale, Zend_Cache, Zend_Registry, Zend_Exception
 * @category   Leek
 * @package    Leek_Calendar
 * @author     Derek Harnanansingh <code@arietis-software.com>
 * @author     Chris Jones <leeked@gmail.com>
 * @see        http://www.arietis-software.com/index.php/2009/05/26/a-php-calendar-class-based-on-zend_date
 * @see        http://arietis-software.com/sandbox/calendar_demo.php
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 */
class Leek_Calendar
{
    const DEFAULT_LOCALE = 'en_US';

    private $locale;
    private $now;
    private $date;
    private $monthNames;
    private $dayNames;
    private $validDates;
    private $numMonthDays;
    private $nextMonth;
    private $prevMonth;
    private $firstDayOfWeek;
    private $numWeeks;

    /**
     * @param int $year
     * @param int $month
     * @param string $locale
     */
    public function __construct($year = null, $month = null, $locale = self::DEFAULT_LOCALE)
    {
        $this->setDate($year, $month, $locale);
    }

    /**
     * @param int $year
     * @param int $month
     * @param string $locale
     */
    public function setDate($year = null, $month = null, $locale = self::DEFAULT_LOCALE)
    {
        // Locale
        if (Zend_Locale::isLocale($locale)) {
            $this->now    = Zend_Date::now($locale); //today
            $this->locale = new Zend_Locale($locale);
        } else {
            $this->now    = Zend_Date::now(self::DEFAULT_LOCALE);
            $this->locale = new Zend_Locale(self::DEFAULT_LOCALE);
        }

        // Date
        $format = 'yyyy-MM';
        $date   = $year . '-' . $month;

        try {
            $this->date = new Zend_Date($date, $format, $this->locale);
        } catch (Zend_Date_Exception $zde) {
            $this->date = new Zend_Date(null, $format, $this->locale);
        }

        // Date params
        $this->initDateParams($this->date);
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        if (Zend_Locale::isLocale($locale)) {
            $this->locale = new Zend_Locale($locale);
            $this->date->setLocale($locale);
        } else {
            $this->locale = new Zend_Locale(self::DEFAULT_LOCALE);
            $this->date->setLocale(self::DEFAULT_LOCALE);
        }
        
        // Update the date params
        $this->initDateParams($this->date);
    }

    private function initDateParams(Zend_Date $date)
    {
        $this->monthNames = Zend_Locale::getTranslationList('Month', $this->locale); //locale month list
        $this->dayNames   = Zend_Locale::getTranslationList('Day', $this->locale); //locale day list
        $this->setValidDateRange(); //locale valid dates
        $this->numMonthDays = $date->get(Zend_Date::MONTH_DAYS); //num days in locale month
        $this->setNextMonth($date); //the next month
        $this->setPrevMonth($date); //the previous month
        $this->firstDayOfWeek = $date->get(Zend_Date::WEEKDAY_DIGIT); //first day of the curr month
        $this->numWeeks = ceil(($this->getFirstDayOfWeek() + $this->getNumMonthDays()) / 7); //num weeks of curr month
    }

    public function setValidDateRange($startOffset = -1, $endOffset = 12)
    {
        $this->validDates = array();
        $startDate = clone $this->now;
        $startMonth = $startDate->subMonth(abs($startOffset));
        $startNum = intval($startMonth->get("M"));
        $this->validDates[$startMonth->get("MMMM yyyy")] = $startMonth->get("MMMM yyyy");
        for ($i = $startNum; $i <= ($startNum + $endOffset); $i ++) {
            $str = $startMonth->addMonth(1)->get("MMMM yyyy");
            $this->validDates[$str] = $str;
        }
        unset($startDate);
        unset($startMonth);
        unset($startNum);
    }

    private function setNextMonth(Zend_Date $date)
    {
        $tempDate = clone $date;
        $this->nextMonth = $tempDate->addMonth(1);
        unset($tempDate);
    }

    private function setPrevMonth(Zend_Date $date)
    {
        $tempDate = clone $date;
        $this->prevMonth = $tempDate->subMonth(1);
        unset($tempDate);
    }

    /**
     * @return array
     */
    public function getValidDates()
    {
        return $this->validDates;
    }

    /**
     * @return array
     */
    public function getMonthNames()
    {
        return $this->monthNames;
    }

    /**
     * @return array
     */
    public function getDayNames()
    {
        return $this->dayNames;
    }

    /**
     * @return Zend_Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getLocaleAsString()
    {
        return $this->locale->toString();
    }

    /**
     * @return int
     */
    public function getFirstDayOfWeek()
    {
        return $this->firstDayOfWeek;
    }

    /**
     * @return Zend_Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getNumMonthDays()
    {
        return $this->numMonthDays;
    }

    /**
     * @return String
     */
    public function getMonthName()
    {
        return $this->date->get("MMMM");
    }

    /**
     * @return String
     */
    public function getMonthShortName()
    {
        return $this->date->get("MMM");
    }

    /**
     * @return int
     */
    public function getMonthNum()
    {
        return $this->date->get("MM");
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->date->get("yyyy");
    }

    /**
     * @return String
     */
    public function getNextMonthName()
    {
        return $this->nextMonth->get("MMMM");
    }

    /**
     * @return int
     */
    public function getNextMonthNum()
    {
        return $this->nextMonth->get("MM");
    }

    /**
     * @return int
     */
    public function getNextMonthYear()
    {
        return $this->nextMonth->get("yyyy");
    }

    /**
     * @return String
     */
    public function getPrevMonthName()
    {
        return $this->prevMonth->get("MMMM");
    }

    /**
     * @return int
     */
    public function getPrevMonthNum()
    {
        return $this->prevMonth->get("MM");
    }
    
    /**
     * @return int
     */
    public function getPrevMonthYear()
    {
        return $this->prevMonth->get("yyyy");
    }

    /**
     * @return int
     */
    public function getNumWeeks()
    {
        return $this->numWeeks;
    }
}