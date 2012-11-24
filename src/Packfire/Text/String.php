<?php
namespace Packfire\Text;

use Packfire\Collection\ArrayList;
use Packfire\Text\Format\IFormattable;

/**
 * String class
 *
 * A String representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Text
 * @since 1.0-sofia
 */
class String implements \Countable, IFormattable {

    /**
     * The internal value of this string
     * @var string
     * @since 1.0-sofia
     */
    private $value = '';

    /**
     * Create a new String object
     * @param string|String $value The string value to initialize with
     * @since 1.0-sofia
     */
    public function __construct($value = '') {
        if($value instanceof String){
            $value = $value->value;
        }
        $this->value = $value;
    }
    
    /**
     * Create a String object from a value
     * @param string $string The string value
     * @return String Returns the string object
     * @since 2.0.4
     */
    public static function from($string){
        return new self($string);
    }
    
    /**
     * Format the string
     * @param string $format The format defined by PHP sprintf() function
     * @param mixed,... $input (optional) Arguments to format into the string
     * @return String Returns the resulting String object
     * @link http://php.net/manual/en/function.sprintf.php
     * @since 2.0.4
     */
    public function format($format){
        $args = func_get_args();
        array_unshift($args, $this->value);
        return new self(call_user_func_array('sprintf', $args));
    }

    /**
     * Get or set the internal string value
     * @param string $value (optional) Set the new value of the string
     * @return string Returns the internal string value.
     * @since 1.0-sofia
     */
    public function value($value = null) {
        if (func_num_args() == 1) {
            $this->value = $value;
        }
        return $this->value;
    }

    /**
     * Strip whitespace from the beginning and end of a string
     * @return String Returns the string trimmed.
     * @since 1.0-sofia
     */
    public function trim() {
        return new self(trim($this->value));
    }

    /**
     * Strip whitespace from the beginning of a string
     * @return String Returns the string trimmed.
     * @since 1.0-sofia
     */
    public function trimLeft() {
        return new self(ltrim($this->value));
    }

    /**
     * Strip whitespace from the end of a string
     * @return String Returns the string trimmed.
     * @since 1.0-sofia
     */
    public function trimRight() {
        return new self(rtrim($this->value));
    }

    /**
     * Split the string into several strings
     * @param String|string $c The delimiter to split the string
     * @return ArrayList Returns the list of split strings
     * @since 1.0-sofia
     */
    public function split($c) {
        $strs = explode($c, $this->value());
        $result = new ArrayList($strs);
        return $result;
    }

    /**
     * Replaces occurances of $a with $b in the string
     * @param String|array|ArrayList $search A string, or a collection of string,
     *               to be searched and replace in
     * @param String|array|ArrayList $replacement A string, or a collection of
     *              string, to be the replacement
     * @return String Returns the resulting string
     * @since 1.0-sofia
     */
    public function replace($search, $replacement) {
        if($search instanceof ArrayList){
            $search = $search->toArray();
        }
        if($replacement instanceof ArrayList){
            $replacement = $replacement->toArray();
        }
        return new String(str_replace($search, $replacement, $this->value()));
    }

    /**
     * Find the position of the first occurance of the string $s in the string
     * @param String|string $string The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer A non-negative number indicating the position of $s in
     *                 the string, or -1 if not found.
     */
    public function indexOf($string, $offset = 0) {
        if (!($string instanceof self)) {
            $string = new self($string);
        }
        $result = strpos($this->value(), $string->value(), $offset);
        if ($result === false) {
            return -1;
        }
        return $result;
    }

    /**
     * Find the position of the last occurance of the string $s in the string
     * @param String|string $s The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer Returns a non-negative number indicating the position of
     *                 s in the string, or -1 if not found.
     * @since 1.0-sofia
     */
    public function lastIndexOf($s, $offset = 0) {
        if (!($s instanceof self)) {
            $s = new self($s);
        }
        $result = strrpos($this->value, $s->value, $offset);
        if ($result === false) {
            return -1;
        }
        return $result;
    }

    /**
     * Find all unique occurances of a substring in the string
     * @param String|string $s The substring to find occurances
     * @param integer $offset (optional) The position to start searching for
     * @return ArrayList Returns the list of index where the substring occurred.
     * @since 1.0-sofia
     */
    public function occurances($s, $offset = 0) {
        if (!($s instanceof self)) {
            $s = new self($s);
        }
        $occurances = new ArrayList();
        while (($idx = $this->indexOf($s, $offset)) >= 0) {
            $occurances->add($idx);
            $offset = $idx + $s->length();
        }
        return $occurances;
    }

    /**
     * Fetch a part of the string.
     * @param integer $start The starting position of the string to fetch from
     * @param integer $length (optional) The number of characters to fetch. If
     *                        this is not specified, the method will fetch from
     *                        start to the end of the string
     * @return String Returns the part of the string fetched.
     * @since 1.0-sofia
     */
    public function substring($start, $length = null) {
        if (func_num_args() == 2) {
            return new self(substr($this->value(), $start, $length));
        } else {
            return new self(substr($this->value(), $start));
        }
    }

    /**
     * Set all alphabets in the string to upper case
     * @return String Returns the resulting string.
     * @since 1.0-sofia
     */
    public function toUpper() {
        return new self(strtoupper($this->value));
    }

    /**
     * Set all alphabets in the string to lower case
     * @return String Returns the resulting string.
     * @since 1.0-sofia
     */
    public function toLower() {
        return new self(strtolower($this->value));
    }

    /**
     * Pad the left side of the string to the desired length
     * @param string $char The string used for padding
     * @param integer $length The maximum amount of characters for the string
     * @return String Returns the resulting string
     * @since 1.0-sofia
     */
    public function padLeft($char, $length) {
        return new self(str_pad($this->value, $length, $char, STR_PAD_LEFT));
    }

    /**
     * Pad the right side of the string to the desired length
     * @param string $char The string used for padding
     * @param integer $length The maximum amount of characters for the string
     * @return String Returns the resulting string
     * @since 1.0-sofia
     */
    public function padRight($char, $length) {
        return new self(str_pad($this->value, $length, $char, STR_PAD_RIGHT));
    }

    /**
     * Pad both sides of the string to the desired length equally
     * @param string $char The string used for padding
     * @param integer $length The maximum amount of characters for the string
     * @return String Returns the resulting string
     * @since 1.0-sofias
     */
    public function padBoth($char, $length) {
        return new self(str_pad($this->value, $length, $char, STR_PAD_BOTH));
    }

    /**
     * Get the length of the string
     * @return integer Returns the length of the string
     * @since 1.0-sofias
     */
    public function length() {
        return strlen($this->value);
    }

    /**
     * Used by Countable for count() functions
     * @return integer
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function count() {
        return $this->length();
    }

    /**
     * For typecasting to string
     * @return string
     * @ignore
     * @internal
     * @since 1.0-sofia
     */
    public function __toString() {
        return $this->value;
    }

}