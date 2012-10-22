<?php
namespace Packfire\Text\Regex;

use Packfire\Text\Regex\Match;
use Packfire\Text\String;
use Packfire\Collection\ArrayList;

/**
 * Regex class
 * 
 * Provides functionality for regular expression matching and replacement
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Text\Regex
 * @since 1.0-sofia
 */
class Regex {

    /**
     * The regular expression
     * @var string
     * @since 1.0-sofia
     */
    private $regex;

    /**
     * Create a new Regex object
     * @param string $regex The regular expression pattern to use
     * @since 1.0-sofia
     */
    public function __construct($regex){
        $this->regex($regex);
    }

    /**
     * Get or set the regular expression that is used in this Regex
     * @param string $regex (optional) If present, the old value will be overriden by this value.
     * @return string Returns the regular expression.
     * @since 1.0-sofia
     */
    public function regex($regex = null){
        if(func_num_args() == 1){
            $this->regex = $regex;
        }
        return $this->regex;
    }

    /**
     * Perform a perl-compatible regular expression (PCRE) match to match 
     * within the subject
     * @param string $subject The input string
     * @return ArrayList A list that contains all the Match found 
     *          in the subject
     * @link http://php.net/manual/en/function.preg-match.php
     * @since 1.0-sofia
     */
    public function match($subject){
        $match = array();
        preg_match($this->regex(), $subject, $match);
        $result = new ArrayList();
        foreach($match as $a){
            $result->add(new Match($this, $a));
        }
        return $result;
    }

    /**
     * Perform a perl-compatible regular expression (PCRE) match to match 
     * within the subject
     * @param string $subject The input string
     * @return boolean Returns true if the expression matches the subject,
     *           or false if otherwise.
     * @link http://php.net/manual/en/function.preg-match.php
     * @since 1.1-sofia
     */
    public function matches($subject){
        return (bool)preg_match($this->regex(), $subject);        
    }

    /**
     * Perform a perl-compatible regular expression (PCRE) match to match all 
     * matches within the subject
     * @param string $subject The input string
     * @return ArrayList Returns the set of match collections
     * @link http://www.php.net/manual/en/function.preg-match-all.php
     * @since 1.0-sofia
     */
    public function matchAll($subject){
        $matches = array();
        preg_match_all($this->regex(), $subject, $matches, PREG_SET_ORDER);
        $finalResult = new ArrayList();
        foreach($matches as $match){
            $result = new ArrayList();
            foreach($match as $c){
                $result->add(new Match($this, $c));
            }
            $finalResult[] = $result;
        }
        return $finalResult;
    }

    /**
     * Perform a perl-compatible regular expression search and replace
     * @param string $subject The input string
     * @param string|array|ArrayList $replacement The string or collection
     *                  of replacements
     * @param integer $limit (Optional) If set, the number of search and replace
     *                  operations will be limited by this limit.
     * @return string|ArrayList
     * @link http://www.php.net/manual/en/function.preg-replace.php
     * @since 1.0-sofia
     */
    public function replace($subject, $replacement, $limit = -1){
        $result = preg_replace($this->regex, $replacement, $subject, $limit);
        if(is_array($result)){
            $result = new ArrayList($result);
        }
        return $result;
    }

    /**
     * Perform a perl-compatible regular expression search and replace using a
     *  callback
     * @param string|ArrayList|array $subject The input string
     * @param callback $callback A callback that will be called and passed an
     *                  array of matched elements in the subject string. The 
     *                  callback should return the replacement string.
     * @param integer $limit (Optional) If set, the number of search and replace
     *                  operations will be limited by this limit.
     * @return string|ArrayList Returns the parsed result
     * @link http://www.php.net/manual/en/function.preg-replace-callback.php
     * @since 1.0-sofia
     */
    public function replaceCallback($subject, $callback, $limit = -1){
        $result = preg_replace_callback($this->regex, $callback,
                $subject, $limit);
        if(is_array($result)){
            $result = new ArrayList($result);
        }
        return $result;
    }
    
    /**
     * Find the position of the first occurrance of regular expression match 
     * in the string
     * @param String|string $subject The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer A non-negative number indicating the position of $s in
     *               the string, or -1 if not found. 
     * @since 1.0-sofia
     */
    public function indexOf($subject, $offset = 0){
        if(!($subject instanceof String)){
            $subject = new String($subject);
        }
        $match = array(array(''));
        $i = preg_match($this->regex(), $subject, $match,
                PREG_OFFSET_CAPTURE , $offset);
        $result = -1;
        if($i){
            $result = $subject->indexOf($match[0][0], $offset);
        }
        return $result;
    }
    
    /**
     * Find the position of the last occurrance of perl compatible regular
     *           expression match in the string
     * @param String|string $subject The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer A non-negative number indicating the position of $s in
     *                       the string, or -1 if not found. 
     * @since 1.0-sofia
     */
    public function lastIndexOf($subject, $offset = 0){
        if(!($subject instanceof String)){
            $subject = new String($subject);
        }
        $match = array(array(''));
        $i = preg_match_all($this->regex(), $subject, $match,
                PREG_SET_ORDER , $offset);
        if($i){
            $match = end($match);
            return $subject->lastIndexOf($match[0], $offset);
        }
        return -1;
    }

    /**
     * Escapes / add slashes to special characters in Regex operation
     * @param string $text The text to perform escaping
     * @return string Returns the escaped string
     * @since 1.0-sofia
     */
    public static function escape($text) {
        $spec  = '"\'^$.[]|()?*+{}/!';
        $text = str_replace("\\n", "\n", $text);
        $text = addcslashes($text, $spec);
        $text = htmlentities($text);
        return $text;
    } 
    
}
