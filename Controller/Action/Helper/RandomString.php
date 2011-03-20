<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Leek_Controller_Action_Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    $Id: RandomString.php 52 2009-06-02 22:11:31Z leeked $
 */

/**
 * Action Helper for generating a random string
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @category   Leek
 * @package    Leek_Controller
 * @subpackage Leek_Controller_Action_Helper
 * @author     Chris Jones <leeked@gmail.com>
 * @link       http://code.google.com/p/leek-zf-components/
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Leek_Controller_Action_Helper_RandomString extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Safe alpha characters
     * @var string
     */
    const LETTERS_SAFE    = 'abcdefghjkpqrtwxyz';

    /**
     * Safe + Non-safe alpha characters
     * @var string
     */
    const LETTERS_NOTSAFE = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * Safe numeric characters
     * @var string
     */
    const NUMBERS_SAFE    = '2346789';

    /**
     * Safe + Non-safe numeric characters
     * @var string
     */
    const NUMBERS_NOTSAFE = '0123456789';

    /**
     * Proxy to alphaNum()
     *
     * @param int   $length         Length of string to return
     * @param bool  $upperCase      Allow upper case characters
     * @param bool  $allowNumbers   Allow numeric characters
     * @param bool  $safeOnly       Use only safe characters
     * @return string
     */
    public function direct($length = 10, $upperCase = true, $allowNumbers = true, $safeOnly = true)
    {
        return $this->alphaNum($length, $upperCase, $allowNumbers, $safeOnly);
    }

    /**
     * Generate a random string of letters and/or numbers.
     *
     * @param int   $length         Length of string to return
     * @param bool  $upperCase      Allow upper case characters
     * @param bool  $allowNumbers   Allow numeric characters
     * @param bool  $safeOnly       Use only safe characters
     * @return string
     */
    public function alphaNum($length = 10, $upperCase = true, $allowNumbers = true, $safeOnly = true)
    {
        $characters = self::LETTERS_SAFE;
        if (!(bool) $safeOnly) {
            $characters = self::LETTERS_NOTSAFE;
        }

        if ((bool) $upperCase) {
            $characters .= strtoupper($characters);
        }

        if ((bool) $allowNumbers) {
            if ((bool) $safeOnly) {
                $characters .= self::NUMBERS_SAFE;
            } else {
                $characters .= self::NUMBERS_NOTSAFE;
            }
        }

        return $this->_generateRandom($characters, $length);
    }

    /**
     * Returns random string of letters ONLY
     *
     * @param int   $length         Length of string to return
     * @param bool  $upperCase      Allow upper case characters
     * @param bool  $safeOnly       Use only safe characters
     * @return string
     */
    public function alphaOnly($length = 10, $upperCase = true, $safeOnly = true)
    {
        return $this->alphaNum($length, $upperCase, false, $safeOnly);
    }

    /**
     * Returns random string of numbers ONLY
     *
     * @param int   $length         Length of string to return
     * @param bool  $safeOnly       Use only safe characters
     * @return string
     */
    public function numbericOnly($length = 10, $safeOnly = false)
    {
        $characters = self::NUMBERS_SAFE;
        if (!(bool) $safeOnly) {
            $characters = self::NUMBERS_NOTSAFE;
        }

        return $this->_generateRandom($characters, $length);
    }

    /**
     * Generate a random string composed of custom set characters
     *
     * @param string $allowedCharacters Characters to compose string with
     * @param int    $length            Length of string to return
     * @return string
     */
    public function custom($allowedCharacters, $length = 10)
    {
        return $this->_generateRandom($allowedCharacters, $length);
    }

    /**
     * Generates random string using $characters
     * to length $length.
     *
     * @param string $characters
     * @param int    $length
     * @return string
     */
    private function _generateRandom($characters, $length)
    {
        $return      = '';
        $length      = (int) $length;
        $charsLength = strlen($characters);

        for ($i = 1; $i <= $length; $i++) {
            $position  = (rand() % $charsLength);
            $character = substr($characters, $position, 1);
            $return   .= $character;
        }

        return $return;
    }
}
