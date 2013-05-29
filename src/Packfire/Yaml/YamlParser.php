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

use Packfire\Yaml\YamlInline;
use Packfire\Collection\Map;
use Packfire\Yaml\YamlReference;
use Packfire\Yaml\YamlPart;
use Packfire\Text\NewLine;

/**
 * Contains constants that identify parts of the document
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Yaml
 * @since 1.0-sofia
 */
class YamlParser
{
    /**
     * The reader to read in data from the input stream
     * @var StreamReader
     * @since 1.0-sofia
     */
    private $read;

    /**
     * The current line
     * @var string
     * @since 1.0-sofia
     */
    private $line = null;

    /**
     * The current line trimmed
     * @var string
     * @since 1.0-elenor
     */
    private $trimmedLine = null;

    /**
     * The indentation level
     * @var integer
     * @since 1.0-elenor
     */
    private $indentation = 0;

    /**
     * A hash map containing the references defined in the YAML document
     * @var Map
     * @since 1.0-sofia
     */
    private $reference;

    /**
     * Create a new YamlParser object
     * @param StreamReader $reader The reader that helps to read the data
     *                                   from the YAML stream.
     * @since 1.0-sofia
     */
    public function __construct($reader)
    {
        $this->read = $reader;
    }

    /**
     * Get the references for the document
     *
     * @return Map Returns a Map containing the $referenceName => $reference
     *              combination. If parsing of a document has yet started, the
     *              method returns null instead. $reference is an instance of
     *              YamlReference.
     * @since 1.0-sofia
     */
    public function reference()
    {
        return $this->reference;
    }

    /**
     * Parse and return the data parsed from the YAML input stream.
     *
     * Note that the method ends parsing when a series of three periods or
     * the end of file is reached, whichever first.
     *
     * @return array Returns the data parsed in an associative array.
     * @since 1.0-sofia
     */
    public function parse()
    {
        $this->reference = new Map(); // reset the reference
                                       //does not support cross document
        $result = $this->parseBlock();

        return $result;
    }

    /**
     * Parse the reader till the start of the YAML document, which is
     * the three hypens (---).
     * @since 1.0-sofia
     */
    public function findDocumentStart()
    {
        $this->read->until(YamlPart::DOC_START);
    }

    /**
     * Read in the next line for process
     * @since 1.0-sofia
     */
    private function nextLine()
    {
        $this->line = '';
        $this->trimmedLine = '';
        $this->indentation = 0;
        if ($this->read->hasMore()) {
            $this->line = YamlValue::stripComment($this->read->line());
            if ($this->line) {
                $this->trimmedLine = trim($this->line);
                if ($this->trimmedLine) {
                    $this->indentation = strpos($this->line, $this->trimmedLine);
                }
            }

            return true;
        } else {
            return null;
        }
    }

    /**
     * Parse a line for key: value
     * @param  string $line The line to parse
     * @return array  Returns an array containing [$key, $value]
     * @since 1.0-sofia
     */
    private function parseKeyValue($line)
    {
        $position = 0;
        $key = YamlInline::load($line)->parseScalar($position,
                        array(YamlPart::KEY_VALUE_SEPARATOR), false);
        $after = $position;
        if ($after >= strlen($line)) {
            $value = null;
        } else {
            $value = trim(substr($line, $after));
            if ($key[0] == '[' || $key[0] == '{') {
                $key = $line;
                $value = null;
            }
        }

        return array($key, $value);
    }

    /**
     * Parse the following embed block.
     * @return array Returns the array of data parsed
     * @since 1.0-sofia
     */
    private function parseBlock()
    {
        $result = array();
        $next = true;
        while (!$this->trimmedLine) {
            if (YamlPart::DOC_END == $this->trimmedLine) {
                $next = false;
                break;
            }

            $next = $this->nextLine();
            if (!$next) {
                break;
            }
        }
        if ($next && $this->trimmedLine) {
            if ($this->trimmedLine[0] == '{') {
                $result = YamlInline::load(trim($this->fetchBlock()))->parseMap();
            } elseif ($this->trimmedLine[0] == '[') {
                $result = YamlInline::load(trim($this->fetchBlock()))->parseSequence();
            }elseif(substr($this->trimmedLine, 0, 2) == YamlPart::SEQUENCE_ITEM_BULLET
                    || $this->trimmedLine == YamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE){
                $result = $this->parseSequenceItems();
            } else {
                if ($this->hasKeyValueLine($this->trimmedLine)) {
                    $result = $this->parseMapItems();
                }
            }
        }

        return $result;
    }

    /**
     * Check if a line has a "key: value" sequence
     * @param  string  $line The line to check
     * @return boolean Returns true if a key value sequence is found, false
     *                 otherwise.
     */
    private function hasKeyValueLine($line)
    {
        list($key, ) = $this->parseKeyValue($line);

        return $key != $line;
    }

    /**
     * Fetch the full value by checking subsequent lines too
     * @param  string       $value What we have for the value so far...
     * @return string|array Returns the value parsed.
     * @since 1.0-sofia
     */
    private function fetchFullValue()
    {
        $result = $this->line;
        if ($this->trimmedLine) {
            $lastchar = substr($this->trimmedLine, -1);
            switch ($this->trimmedLine[0]) {
                case '{':
                    if ($lastchar != '}') {
                        $result = trim($this->fetchBlock());
                    }
                    $result = YamlInline::load($result)->parseMap();
                    $this->nextLine();
                    break;
                case '[':
                    if ($lastchar != ']') {
                        $result = trim($this->fetchBlock());
                    }
                    $result = YamlInline::load($result)->parseSequence();
                    $this->nextLine();
                    break;
                case '|': // newlines preserved literal blocks
                    $this->nextLine();
                    $result = NewLine::neutralize(trim($this->fetchBlock()));
                    break;
                case '>': // folded literal block
                    $this->nextLine();
                    $result = NewLine::neutralize(trim($this->fetchBlock()));
                    $result = preg_replace(array('`\n\s+([^\s]+)`', '`([^\n]+)\n([^\n]+)`'),
                            array("\n".'$1', '$1 $2'), $result);
                    $result = str_replace("\n\n", "\n", $result);
                    break;
                case '*': // refer to reference
                    $referenceName = substr($this->trimmedLine, 1);
                    if ($this->reference->keyExists($referenceName)) {
                        $result = $this->reference->get($referenceName);
                    }
                    $this->nextLine();
                    break;
                case '&': // reference creation
                    $referenceName = substr($this->trimmedLine, 1);
                    $this->nextLine();
                    $result= new YamlReference($this->parseBlock());
                    $this->reference[$referenceName] = $result;
                    break;
                default: // normal scalar value
                    $result = YamlInline::load($this->trimmedLine)->parseScalar();
                    $this->nextLine();
                    break;
            }
        }

        return $result;
    }

    /**
     * Parse the sequence block
     * @param  string $minLevel The indentation level for this block
     * @return array  Returns an array of items from parsing the sequence block.
     * @since 1.0-sofia
     */
    private function parseSequenceItems()
    {
        $result = array();

        $minLevel = $this->indentation;
        while (!$this->trimmedLine || $minLevel == $this->indentation) {
            if (YamlPart::DOC_END == $this->trimmedLine) {
                break;
            }

            $next = true;
            if ($this->trimmedLine) {
                $bulletCheck = substr($this->trimmedLine, 0, 2);
                if(($bulletCheck == YamlPart::SEQUENCE_ITEM_BULLET
                        || ltrim($this->line) == YamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE)){

                    $lineValue = substr($this->trimmedLine, 2);
                    $cleanLineValue = YamlValue::stripQuote($lineValue);
                    list($key, $value) = $this->parseKeyValue($cleanLineValue);

                    if ($key == $cleanLineValue && '' !== $key && null === $value) {
                        // - value
                        $this->line = str_repeat(' ', $this->indentation + 2) . $lineValue;
                        $this->trimmedLine = $lineValue;
                        $this->indentation += 2;
                        $result[] = $this->fetchFullValue();
                        $next = false;
                    } elseif ('' === $key && $value === null) {
                        // -
                        //   value
                        $this->nextLine();
                        $result[] = $this->parseBlock();
                        $next = false;
                    } else {
                        // - key: value
                        $this->line = str_repeat(' ', $this->indentation + 2) . $lineValue;
                        $this->trimmedLine = $lineValue;
                        $this->indentation += 2;
                        $result[] = $this->parseMapItems();
                        $next = false;
                    }
                }
            }
            if ($next) {
                $next = $this->nextLine();
                if (!$next) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Parse the map block
     * @param  integer $minLevel The indentation level for the map block.
     * @return array   Returns an associative array containing key value data
     *               parsed from the map block.
     * @since 1.0-sofia
     */
    private function parseMapItems()
    {
        $result = array();
        $minLevel = $this->indentation;
        while (!$this->trimmedLine || $minLevel == $this->indentation) {
            if (YamlPart::DOC_END == $this->trimmedLine) {
                break;
            }
            if ($this->trimmedLine) {
                list($key, $value) = $this->parseKeyValue($this->trimmedLine);
                if ($value === null) {
                    // key is on its own...
                    $this->nextLine();
                    $value = $this->parseBlock();
                } else {
                    // we've got a value and key!
                    $this->line = $value;
                    $this->trimmedLine = trim($value);
                    $this->indentation += 2;
                    $value = $this->fetchFullValue();
                }
                $result[$key] = $value;
            } else {
                $next = $this->nextLine();
                if (!$next) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Fetch a block based on the indentation
     * @return string The block's data.
     * @since 1.0-sofia
     */
    private function fetchBlock()
    {
        $text = '';
        $minIndent = $this->indentation;

        while (!$this->trimmedLine || $minIndent <= $this->indentation) {
            if (!$this->trimmedLine) {
                $text .= "\n";
            } else {
                $text .= substr($this->line, $minIndent);
            }
            $next = $this->nextLine();
            if (!$next) {
                break;
            }
        }

        return $text;
    }

}
