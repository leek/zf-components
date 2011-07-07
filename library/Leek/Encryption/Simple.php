<?php
/**
 * Leek - Zend Framework Components
 *
 * @category   Leek
 * @package    Leek_Encryption
 * @author     A J Marston <http://www.tonymarston.net>
 * @author     M. Kolar <http://mkolar.org>
 * @author     Chris Jones <leeked@gmail.com>
 * @license    GNU General Public Licence
 */

/**
 * A simple reversible password encryption routine.
 * 
 * @category   Leek
 * @package    Leek_Encryption
 * @author     A J Marston <http://www.tonymarston.net>
 * @author     M. Kolar <http://mkolar.org>
 * @author     Chris Jones <leeked@gmail.com>
 * @license    GNU General Public Licence
 */
class Leek_Encryption_Simple
{
    /**
     * Each of these two keys must contain the same characters, but in a different order.
     * NOTE:
     *  - Use only printable characters from the ASCII table.
     *  - Do not use single quote, double quote or backslash.
     *  - Each character can only appear once in each string.
     * @var string 
     */
    protected $_scramble1 = '!#$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
    
    /**
     * @var string
     */
    protected $_scramble2 = 'f^jAE]okIOzU[2&q1{3`h5w_794p@6s8?BgP>dFV=mD<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(;Lt-R}Ma,NvW+Ynb*0X';
    
    /**
     * @var array
     */
    protected $_errors = array();
    
    /**
     * @var float
     */
    protected $_adjustment = 1.75;
    
    /**
     * @var int
     */
    protected $_modulus = 3;
    
    /**
     * @param type $options 
     */
    public function __construct($options = array())
    {
        foreach ($options as $key => $value) {
            $key = strtolower($key);
            switch ($key) {
                case 'scramble1':
                    $this->_scramble1 = $value;
                    break;
                case 'scramble2':
                    $this->_scramble2 = $value;
                    break;
                case 'adjustment':
                    $this->setAdjustment($value);
                    break;
                case 'modulus':
                case 'mod':
                    $this->setModulus($value);
                    break;
            }
        }
        
        if (strlen($this->_scramble1) <> strlen($this->_scramble2)) {
            throw new Exception('Scramble1 and Scramble2 do not have the same length');
        } 
    }
    
    /**
     * @param type $key
     * @param type $source
     * @param type $sourceLength
     * @return string 
     */
    public function encrypt($key, $source, $sourceLength = 0)
    {
        $this->_errors = array();

        // Convert $key into a sequence of numbers
        $fudgefactor = $this->_convertKey($key);
        if ($this->_errors)
            return;

        if (empty($source)) {
            $this->_errors[] = 'No value has been supplied for encryption';
            return;
        }
        
        // Pad $source with spaces up to $sourceLength
        $source = str_pad($source, $sourceLength);

        $target = null;
        $factor2 = 0;

        for ($i = 0; $i < strlen($source); $i++) {
            
            // Extract a (multibyte) character from $source
            if (function_exists('mb_substr')) {
                $char1 = mb_substr($source, $i, 1);
            } else {
                $char1 = substr($source, $i, 1);
            }
            
            // Identify its position in $scramble1
            $num1 = strpos($this->_scramble1, $char1);
            if ($num1 === false) {
                $this->_errors[] = "Source string contains an invalid character ($char1)";
                return;
            }
            
            // Get an adjustment value using $fudgefactor
            $adj = $this->_applyFudgeFactor($fudgefactor);

            $factor1 = $factor2 + $adj;           // Accumulate in $factor1
            $num2    = round($factor1) + $num1;   // Generate offset for $scramble2
            $num2    = $this->_checkRange($num2); // Check range
            $factor2 = $factor1 + $num2;          // Accumulate in $factor2
            
            // Extract (multibyte) character from $scramble2
            if (function_exists('mb_substr')) {
                $char2 = mb_substr($this->_scramble2, $num2, 1);
            } else {
                $char2 = substr($this->_scramble2, $num2, 1);
            }
            
            // Append to $target string
            $target .= $char2;

        }

        return $target;
    }

    /**
     * @param type $key
     * @param type $source
     * @return string 
     */
    public function decrypt($key, $source)
    {
        $this->_errors = array();

        // Convert $key into a sequence of numbers
        $fudgefactor = $this->_convertKey($key);
        if ($this->_errors)
            return;

        if (empty($source)) {
            $this->_errors[] = 'No value has been supplied for decryption';
            return;
        }

        $target = null;
        $factor2 = 0;

        for ($i = 0; $i < strlen($source); $i++) {
            
            // Extract a (multibyte) character from $source
            if (function_exists('mb_substr')) {
                $char2 = mb_substr($source, $i, 1);
            } else {
                $char2 = substr($source, $i, 1);
            }
            
            // Identify its position in $scramble2
            $num2 = strpos($this->_scramble2, $char2);
            if ($num2 === false) {
                $this->_errors[] = "Source string contains an invalid character ($char2)";
                return;
            }
            
            // Get an adjustment value using $fudgefactor
            $adj = $this->_applyFudgeFactor($fudgefactor);

            $factor1 = $factor2 + $adj;           // Accumulate in $factor1
            $num1    = $num2 - round($factor1);   // Generate offset for $scramble1
            $num1    = $this->_checkRange($num1); // Check range
            $factor2 = $factor1 + $num2;          // Accumulate in $factor2
            
            // Extract (multibyte) character from $scramble1
            if (function_exists('mb_substr')) {
                $char1 = mb_substr($this->_scramble1, $num1, 1);
            } else {
                $char1 = substr($this->_scramble1, $num1, 1);
            }
            
            // Append to $target string
            $target .= $char1;

        }

        return rtrim($target);
    }

    /**
     * @return float 
     */
    public function getAdjustment()
    {
        return $this->_adjustment;
    }

    /**
     * @return int 
     */
    public function getModulus()
    {
        return $this->_modulus;
    }

    /**
     * @param float $adj
     * @return Leek_Encryption_Simple 
     */
    public function setAdjustment($adj)
    {
        $this->_adjustment = (float) $adj;
        return $this;
    }

    /**
     * @param int $mod
     * @return Leek_Encryption_Simple 
     */
    public function setModulus($mod)
    {
        // Must be a positive whole number
        $this->_modulus = (int) abs($mod);
        return $this;
    }

    /**
     * Return an adjustment value  based on the contents of $fudgefactor
     * NOTE: $fudgefactor is passed by reference so that it can be modified
     * @param array $fudgefactor
     * @return type 
     */
    protected function _applyFudgeFactor(&$fudgefactor)
    {
        $fudge = array_shift($fudgefactor);      // extract 1st number from array
        $fudge = $fudge + $this->_adjustment;    // add in adjustment value
        $fudgefactor[] = $fudge;                 // put it back at end of array

        if (!empty($this->_modulus)) {           // if modifier has been supplied
            if ($fudge % $this->_modulus == 0) { // if it is divisible by modifier
                $fudge = $fudge * -1;            // make it negative
            }
        }

        return $fudge;
    }

    /**
     * Check that $num points to an entry in $this->_scramble1
     * @param type $num
     * @return type 
     */
    protected function _checkRange($num)
    {
        // Round up to nearest whole number
        $num   = round($num); 
        $limit = strlen($this->_scramble1);

        while ($num >= $limit) {
            // Value too high, so reduce it
            $num = $num - $limit;   
        }
        
        while ($num < 0) {
            // Value too low, so increase it
            $num = $num + $limit;   
        }

        return $num;
    }

    /**
     * Convert $key into an array of numbers
     * @param type $key
     * @return type 
     */
    protected function _convertKey($key)
    {
        if (empty($key)) {
            $this->_errors[] = 'No value has been supplied for the encryption key';
            return;
        }

        // First entry in array is length of $key
        $array[] = strlen($key);    
        $tot     = 0;
        for ($i = 0; $i < strlen($key); $i++) {
            
            // Extract a (multibyte) character from $key
            if (function_exists('mb_substr')) {
                $char = mb_substr($key, $i, 1);
            } else {
                $char = substr($key, $i, 1);
            }
            
            // Identify its position in $scramble1
            $num = strpos($this->_scramble1, $char);
            if ($num === false) {
                $this->_errors[] = "Key contains an invalid character ($char)";
                return;
            }

            $array[] = $num;        // store in output array
            $tot     = $tot + $num; // accumulate total for later
        }

        // Insert total as last entry in array
        $array[] = $tot;
        return $array;
    }

}