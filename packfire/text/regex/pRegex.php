<?php
pload('packfire.text.regex.pRegexMatch');
pload('packfire.text.pString');
pload('packfire.collection.pMap');

/**
 * Provides functionality for regular expression matching and replacement
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.text.regex
 * @since 1.0-sofia
 */
class pRegex {

    /**
     * The regular expression
     * @var string
     * @since 1.0-sofia
     */
    private $regex;

    /**
     * Create a new pRegex
     * @param string $regex The regular expression pattern that this pRegex will use
     */
    public function __construct($regex){
        $this->regex($regex);
    }

    /**
     * Get or set the regular expression that is used in this pRegex
     * @param string $regex (optional) If present, the old value will be overriden by this value.
     * @return string Returns the regular expression.
     */
    public function regex($regex = null){
        if(func_num_args() == 1){
            $this->regex = $regex;
        }
        return $this->regex;
    }

    /**
     * Perform a perl-compatible regular expression (PCRE) match to match within the subject
     * @param string $subject The input string
     * @return pList A list that contains all the RaiseRegexMatch found in the subject
     * @link http://php.net/manual/en/function.preg-match.php
     */
    public function match($subject){
        $match = array();
        preg_match($this->regex(), $subject, $match);
        $result = new pList();
        foreach($match as $a){
            $result->add(new pRegexMatch($this, $a));
        }
        return $result;
    }

    /**
     * Perform a perl-compatible regular expression (PCRE) match to match all matches within the subject
     * @param string $subject The input string
     * @return pList Returns the set of match collections
     * @link http://www.php.net/manual/en/function.preg-match-all.php
     */
    public function matchAll($subject){
        $m = array();
        preg_match_all($this->regex(), $subject, $m, PREG_SET_ORDER);
        $finalResult = new pList();
        foreach($m as $a){
            $result = new pList();
            foreach($a as $c){
                $result->add(new pRegexMatch($this, $c));
            }
            $finalResult[] = $result;
        }
        return $finalResult;
    }

    /**
     * Perform a perl-compatible regular expression search and replace
     * @param string $subject The input string
     * @param string|array|pList $replacement The string or collection of replacements
     * @param integer $limit (Optional) If set, the number of search and replace operations will be limited by this limit.
     * @return string|pList
     * @link http://www.php.net/manual/en/function.preg-replace.php
     */
    public function replace($subject, $replacement, $limit = -1){
        $result = preg_replace($this->regex, $replacement, $subject, $limit);
        if(is_array($result)){
            $result = new pList($result);
        }
        return $result;
    }

    /**
     * Perform a perl-compatible regular expression search and replaceusing a callback
     * @param string|pList|array $subject The input string
     * @param callback $callback A callback that will be called and passed an array of matched elements in the subject string. The callback should return the replacement string.
     * @param integer $limit (Optional) If set, the number of search and replace operations will be limited by this limit.
     * @return string|pList
     * @link http://www.php.net/manual/en/function.preg-replace-callback.php
     */
    public function replaceCallback($subject, $callback, $limit = -1){
        $result = preg_replace_callback($this->regex, $callback, $subject, $limit);
        if(is_array($result)){
            $result = new pList($result);
        }
        return $result;
    }
    
    /**
     * Find the position of the first occurrance of regular expression match in the string
     * @param pString|string $s The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer A non-negative number indicating the position of $s in the string, or -1 if not found. 
     */
    public function indexOf($subject, $offset = 0){
        if(!($subject instanceof pString)){
            $subject = new pString($subject);
        }
        $match = array(array(''));
        $i = preg_match($this->regex(), $subject, $match, PREG_OFFSET_CAPTURE , $offset);
        $result = -1;
        if($i){
            $result = $subject->indexOf($match[0][0], $offset);
        }
        return $result;
    }
    
    /**
     * Find the position of the last occurrance of perl compatible regular expression match in the string
     * @param pString|string $s The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer A non-negative number indicating the position of $s in the string, or -1 if not found. 
     */
    public function lastIndexOf($subject, $offset = 0){
        if(!($subject instanceof pString)){
            $subject = new pString($subject);
        }
        $match = array(array(''));
        $i = preg_match_all($this->regex(), $subject, $match, PREG_SET_ORDER , $offset);
        if($i){
            $match = end($match);
            return $subject->lastIndexOf($match[0], $offset);
        }
        return -1;
    }

    /**
     * Escapes / add slashes to special characters in Regex operation
     * @param string $text The text to perform escaping
     * @return string
     * @static
     */
    public static function escape($text) {
        $spec  = '"\'^$.[]|()?*+{}/!';
        $text = str_replace("\\n", "\n", $text);
        $text = addcslashes($text, $spec);
        $text = htmlentities($text);
        return $text;
    } 
    
}
