<?php
namespace Packfire\Text;

/**
 * NewLine class
 * 
 * Newline constants and functionality to work with newlines
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Text
 * @since 1.0-sofia
 */
class NewLine {

    /**
     * Newline in Windows
     * @since 1.0-sofia
     */
    const WINDOWS = "\r\n";

    /**
     * Newline in Macintosh
     * @since 1.0-sofia
     */
    const MACINTOSH = "\r";

    /**
     * Newline in *nix
     * @since 1.0-sofia
     */
    const UNIX = "\n";
    
    /**
     * The HTML &lt;br&gt;
     * @since 1.0-sofia 
     */
    const HTML_BR = '<br>';
    
    /**
     * The HTML &lt;br /&gt;
     * @since 1.0-sofia 
     */
    const XHTML_BR = '<br />';
    
    /**
     * Neutralize all newline character format in a string
     * @param string $string The string to be neutralized
     * @param string $target (optional) The target newline to use. 
     *              Defaults to pNewline::UNIX.
     *              You can use constants from pNewline or any text to
     *              replace the newline.
     * @return string Returns the neutralized string
     * @since 1.0-sofia
     */
    public static function neutralize($string, $target = self::UNIX){
        $string = str_replace(array("\r\n", "\r"), array(self::UNIX, self::UNIX), $string);
        if($target != self::UNIX){
            $string = str_replace(self::UNIX, $target, $string);
        }
        return $string;
    }
    
}