<?php
namespace Packfire\Text;

/**
 * Text class
 * 
 * Provides text services and functionality
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Text
 * @since 1.0-sofia
 */
class Text {

    /**
     * An array of line breaking points
     * @var array
     * @since 1.0-sofia
     */
    private static $linebreaks = array(' ', '-', '.', ';', ')', ']');

    /**
     * Truncate string if it is longer than a specified length
     * 
     * Note that if the breaking occurs in a middle of a word, the method will
     * find a line breaking character before the word to break the line
     * 
     * @param string $string The original string
     * @param integer $length The maximum length of the
     * @param string $ending (optional) The ending to append if length of string
     *                       is too long. Defaults to '...'
     * @return string Returns the resulting string
     * @since 1.0-sofia
     */
    public static function truncate($string, $length, $ending = '...') {
        if (strlen($string) < $length) {
            return $string;
        }
        //$before = substr($string, $length - 1, 1);
        $that = substr($string, $length, 1);
        //$after = substr($string, $length + 1, 1);
        if (!in_array($that, self::$linebreaks)) {
            $i = $length;
            while ($i-- > 0) {
                $c = $string[$i];
                if (in_array($c, self::$linebreaks)) {
                    break;
                }
            }
            $length = $i;
        }
        return substr($string, 0, $length) . $ending;
    }

    /**
     * Highlight specific phrase in a string case-insensitively
     * @param String|string $string The original string to search in
     * @param string|IList|array $phrase The phrase to highlight
     * @param string $format (optional) The format of the highlight. Note that
     *                       $1 refers to the original text to be highlighted.
     *                       Defaults to '<b>$1</b>'
     * @return string Returns the resulting string
     * @since 1.0-sofia
     */
    public static function highlight($string, $phrase, $format = '<b>$1</b>') {
        if (is_array($phrase) || $phrase instanceof IList) {
            foreach ($phrase as &$p) {
                $p = '`(?![^<]+>)(' . $p . ')(?![^<]+>)`is';
            }
        } else {
            $phrase = '`(?![^<]+>)(' . $phrase . ')(?![^<]+>)`is';
        }
        return preg_replace($phrase, $format, $string);
    }

    /**
     * Strip off all HTML tags in a string.
     * @param String|string $str The string to strip HTML tags
     * @param string $allowed (optional) The tags to be allowed
     * @return string Returns the resulting string.
     * @link http://php.net/strip-tags
     * @since 1.0-sofia
     */
    public static function stripTags($str, $allowed = null) {
        return strip_tags($str, $allowed);
    }

    /**
     * Create a comma separated list where the last two items are joined with
     * 'and', forming a natural English list.
     * 
     * @param array|IList $list The array or collection of items
     * @param string $and (optional) The 'and' word to use. Defaults to 'and'.
     * @param string $separator (optional) The comma separator to use. Defaults
     *                          to ', '.
     * @return string Returns the resulting string
     * @since 1.0-sofia
     */
    public static function listing($list, $and = 'and', $separator = ', ') {
        if (count($list) > 1) {
            return implode($separator, array_slice($list, null, -1)) . ' ' 
                    . $and . ' ' . array_pop($list);
        } else {
            return array_pop($list);
        }
    }

    /**
     * Rotate the string through 13 characters
     * @param String|string $str The string to be rotated
     * @return string Returns the resulting string.
     * @since 1.0-sofia
     */
    public static function rotate13($str) {
        return str_rot13($str);
    }

    /**
     * Rotate the string through 47 characters
     * @param String|string $str The string to be rotated
     * @return string Returns the resulting string.
     * @since 1.0-sofia
     */
    public static function rotate47($str) {
        return strtr($str, '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                . '[\]^_`abcdefghijklmnopqrstuvwxyz{|}~', 'PQRSTUVWXYZ[\]^_`'
                . 'abcdefghijklmnopqrstuvwxyz{|}~!"#$%&\'()*+,-./0123456789'
                . ':;<=>?@ABCDEFGHIJKLMNO');
    }
    
}