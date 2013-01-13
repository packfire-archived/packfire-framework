<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Text;

/**
 * Provides functionality in changing forms of words
 * inflect (verb) - Change the form of (a word) to express a particular 
 * grammatical function or attribute, typically tense, mood, person, number
 * and gender.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Text
 * @since 1.0-sofia
 */
class Inflector {

    /**
     * Contains an array of irregular plural words
     * singular => plural
     * @var array
     * @since 1.0-sofia
     */
    private static $irregularPlural = array(
        'deer' => 'deer',
        'sheep' => 'sheep',
        'moose' => 'moose',
        'bison' => 'bison',
        'salmon' => 'salmon',
        'pike' => 'pike',
        'trout' => 'trout',
        'swine' => 'swine',
        'ox' => 'oxen',
        'child' => 'children',
        'foot' => 'feet',
        'goose' => 'geese',
        'louse' => 'lice',
        'mouse' => 'mice',
        'tooth' => 'teeth',
        'man' => 'men',
        'woman' => 'women',
        'index' => 'indices',
        'matrix' => 'matrices',
        'vertex' => 'vertices',
        'axis' => 'axes',
        'crisis' => 'crises',
        'testis' => 'testes',
        'series' => 'series',
        'viris' => 'viruses',
        'meatus' => 'meatus',
        'meat' => 'meats',
        'beau' => 'beaux',
        'chateau' => 'chateaux',
        'samurai' => 'samurai',
        'bacteria' => 'bacteria',
        'data' => 'data',
        'graffiti' => 'graffiti',
        'paparazzi' => 'paparazzi',
        'spaghetti' => 'spaghetti',
        'biscotti' => 'boscotti',
        'phalanx' => 'phalanges',
        'viscus' => 'viscera',
        'consortium' => 'consortia',
        'symposium' => 'symposia',
        'person' => 'people',
        'clothes' => 'clothes',
        'cloth' => 'cloth',
        'measles' => 'measles',
        'phenomenon' => 'phenomena',
        'polyhedron' => 'polyhedra',
        'criterion' => 'criteria',
        'anus' => 'anuses',
        'boy' => 'boys',
        'maze' => 'mazes',
        'day' => 'days',
        'has' => 'have',
        'is' => 'are'
    );
    
    /**
     * Get the position of the first uppercase letter in the subject
     * @param string $subject The subject line to search
     * @return integer Returns the number of the first uppercase letter.
     */
    public static function firstUpperCase($subject){
        $replaced = preg_replace('`^([a-z]*)[A-Z].*`', '$1', $subject);
        return $replaced == $subject ? false : strlen($replaced);
    }

    /**
     * Attempt to retain the casing form of the original word into the new word
     * when inflecting between plural and singular.
     * @param string $originalWord The original to refer to
     * @param string $newWord The word to transform
     * @return string Returns the new word in the form of the original word.
     * @since 1.0-sofia
     */
    private static function retainForm($originalWord, $newWord){
        if(self::isWordLowerCase($originalWord)){
            return strtolower($newWord);
        }
        if(self::isWordUpperCase($originalWord)){
            return strtoupper($newWord);
        }
        if(self::isCapitalLetterWord($originalWord)){
            return ucfirst($newWord);
        }
        return $newWord;
    }

    /**
     * Determine whether a word has all letters in their capital form
     * @param string $word The word to check
     * @return boolean Returns true if all letters in the word are in their 
     *              capital form, false otherwise.
     * @since 1.0-sofia
     */
    public static function isWordUpperCase($word){
        return strtoupper($word) == $word;
    }

    /**
     * Determine whether a word has all letters in their lower case form
     * @param string $word The word to check
     * @return boolean Returns true if all letters in the word are in their 
     *              lower case form, false otherwise.
     * @since 1.0-sofia
     */
    public static function isWordLowerCase($word){
        return strtolower($word) == $word;
    }

    /**
     * Determine whether a word has a capital letter for it's first letter
     * @param string $word The word to check
     * @return boolean Returns true if the first letter of the word is capital,
     *                 false otherwise
     * @since 1.0-sofia
     */
    public static function isCapitalLetterWord($word){
        if(strlen($word) > 0){
            return self::isWordUpperCase($word[0]);
        }
        return false;
    }

    /**
     * Convert a word into its singular form
     * @param string $word The word to be converted
     * @return string Returns the singular form of $word
     * @since 1.0-sofia
     */
    public static function singular($word){

        $lc = strtolower($word);
        
        // already a singular, return it!
        if(isset(self::$irregularPlural[$lc])){
            return $word;
        }
        
        // check for special words
        $swk = array_search($lc, self::$irregularPlural);
        if($swk !== false){
            return self::retainForm($word, $swk);
        }

        // donkey => donkeys
        if(substr($lc, -4) == 'keys'){
            return self::retainForm($word, substr($word, 0, strlen($word) - 1));
        }

        $aeiou = array('a', 'e', 'i', 'o', 'u');
        // penny => pennies
        if(substr($lc, -3) == 'ies' && !in_array(substr($lc, -4, 1), $aeiou)){
            return self::retainForm($word, substr($word, 0, strlen($word) - 3) . 'y');
        }

        // alumnus => alumni
        if(substr($lc, -1) == 'i'){
            return self::retainForm($word, substr($word, 0, strlen($word) - 1) . 'us');
        }
        // box => boxes
        // matches
        // mashes
        $endWith = array('xes', 'ses', 'zes', 'shes', 'ches');
        foreach($endWith as $ew){
            if(substr($lc, -strlen($ew)) == $ew){
                return self::retainForm($word, substr($word, 0, strlen($w) - 2));
            }
        }

        // oh well
        if(substr($lc, -1) == 's'){
            return self::retainForm($word, substr($word, 0, strlen($word) - 1));
        }
        return $word;
    }

    /**
     * Convert a word into its plural form
     * @param string $word The word to be converted
     * @return string Returns the plural form of $word
     * @since 1.0-sofia
     */
    public static function plural($word){

        $lc = strtolower($word);
        
        // already a plural? return it!
        if(in_array($lc, self::$irregularPlural)){
            return $word;
        }
        
        // check for special words
        if(isset(self::$irregularPlural[$lc])){
            return self::retainForm($word, self::$irregularPlural[$lc]);
        }
        
        // donkey => donkeys
        if(substr($lc, -3) == 'key'){
            return self::retainForm($word, $word . 's');
        }
        if(substr($lc, -4) == 'keys'){
            return $word;
        }
        
        $aeiou = array('a', 'e', 'i', 'o', 'u');
        // penny => pennies
        if(substr($lc, -1) == 'y' && !in_array(substr($lc, -2, 1), $aeiou)){
            return self::retainForm($word,
                    substr($word, 0, strlen($word) - 1) . 'ies');
        }
        // penny => pennies
        if(substr($lc, -3) == 'ies' && !in_array(substr($lc, -4, 1), $aeiou)){
            return $word;
        }

        // alumnus => alumni
        if(substr($lc, -2) == 'us'){
            return self::retainForm($word,
                    substr($word, 0, strlen($word) - 2) . 'i');
        }
        
        $endWith = array('xes', 'ses', 'zes', 'shes', 'ches');
        foreach($endWith as $ew){
            if(substr($lc, -strlen($ew)) == $ew){
                return $word;
            }
        }
        
        // box => boxes
        $endWith = array('x', 's', 'z', 'sh', 'ch');
        foreach($endWith as $ew){
            if(substr($lc, -strlen($ew)) == $ew){
                return self::retainForm($word, $word . 'es');
            }
        }
        
        if(substr($lc, -1) == 's'){
            // since the word ends with an s, then just return it,
            // probably in its plural form already
            return $word;
        }else{
            // oh well we have no choice
            return self::retainForm($word, $word . 's');
        }
    }

    /**
     * Get the singular or plural form of a word based on a quantifier before the word
     * @param integer|double $n The quantifier
     * @param string $singular The singular form of the word
     * @param string $plural (optional) The plural form of the word. If not
     *          supplied, the plural form will be determined
     *          from Inflector::plural()
     * @return string
     * @see Inflector::plural()
     * @since 1.0-sofia
     */
    public static function quantify($num, $singular, $plural = null){
        return $num == 1 ? $singular :
            ($plural ? $plural : self::plural($singular));
    }

}
