<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Yaml;

use Packfire\Yaml\YamlPart;

/**
 * Provides API for working on YAML values.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Yaml
 * @since 1.0-sofia
 */
class YamlValue
{
    /**
     * Strip comments off the text
     *
     * If the text has multiple lines, the method will remove comments from all
     * the lines.
     *
     * @param  string $line The text to remove comments from.
     * @return string Returns the text without any comments
     * @since 1.0-sofia
     */
    public static function stripComment($line)
    {
        $length = strlen($line);
        $position = 0;
        $openQuote = null;
        $start = null;
        $end = null;
        $doLoop = true;
        while ($position < $length && $doLoop) {
            switch ($line[$position]) {
                case '\\':
                    ++$position;
                    break;
                case "\n":
                    if (!$openQuote && $start !== null) {
                        $end = $position;
                        $start = null;
                        $position = $length;
                    }
                    break;
                case '#':
                    if ($openQuote === null) {
                        $start = $position;
                        $position = $length;
                    }
                    break;
                case '"':
                case '\'':
                    if ($openQuote) {
                        if ($openQuote == $line[$position]) {
                            $openQuote = null;
                        }
                    } else {
                        $openQuote = $line[$position];
                        $pos = strpos($line, $line[$position], $position + 1);
                        if ($pos !== false) {
                            $position = $pos - 1;
                        }
                    }
                    break;
            }
            ++$position;
        }
        if ($start !== null) {
            if ($end === null) {
                $end = $length;
            }
            $line = substr($line, 0, $start) . substr($line, $end);
        }

        return $line;
    }

    /**
     * Check whether if text is quoted or not.
     * @param  string  $text The text to check
     * @return boolean Returns true if the text is quoted, false otherwise.
     * @since 1.0-sofia
     */
    public static function isQuoted($text)
    {
        $text = trim($text);
        $len = strlen($text);

        return $len > 1 && in_array($text[0], YamlPart::quotationMarkers()) && $text[0] == $text[$len - 1];
    }

    /**
     * Strip quotation marks if the text is wrapped by them.
     * @param  string $text The text to strip quotes
     * @return string Returns the processed string
     * @since 1.0-sofia
     */
    public static function stripQuote($text)
    {
        $result = $text;
        if (self::isQuoted($text)) {
            $result = substr($text, 1, strlen($text) - 2);
        }

        return $result;
    }

    /**
     * Process a scalar value
     * @param  string $scalar The value to process
     * @return string Returns the processed scalar value.
     * @since 1.0-sofia
     */
    public static function translateScalar($scalar)
    {
        $result = $scalar;
        $quoted = self::isQuoted($result);
        if (!$quoted || ($quoted && $result[0] != '\'')) {
            $result = self::unescape($result);
        } elseif ($quoted && $result[0] == '\'') {
            $result = self::unescapeQuote($result);
        }
        if ($quoted) {
            $result = self::stripQuote($result);
        }
        if (is_string($result)) {
            switch (strtolower($scalar)) {
                case 'true':
                    $result = true;
                    break;
                case 'false':
                    $result = false;
                    break;
                case 'null':
                    $result = null;
                    break;
            }
        } elseif (is_numeric($result)) {
            $result += 0;
        }

        return $result;
    }

    /**
     * Unescapes slash-quotes within the quotes if the text is quoted
     * @param  string $text The text to process
     * @return string Returns the processed text
     * @since 1.0-elenor
     */
    private static function unescapeQuote($text)
    {
        return  preg_replace('`([^\\\\])\\\\' . $text[0] . '`ism', '$1'.$text[0], $text);
    }

    /**
     * Process and unescape characters from a text
     * @param  string $text The text to process
     * @return string Returns the processed text
     * @since 1.0-sofia
     */
    public static function unescape($text)
    {
        if (self::isQuoted($text)) {
            $text = self::unescapeQuote($text);
        }
        $replace = array(
            '\n' => "\n",
            '\r' => "\r",
            '\t' => "\t",
            '\0' => "\0",
        );

        return str_replace(array_keys($replace), $replace, $text);
    }
}
