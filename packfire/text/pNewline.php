<?php

/**
 * Newline constants
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.text
 * @since 1.0-sofia
 */
class pNewline {

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
     * Neutralize all newline character format in a string
     * @param string $string The string to be neutralized
     * @param string $target (optional) The target newline to use. Defaults to pNewline::UNIX.
     * @return string Get the neutralized string
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