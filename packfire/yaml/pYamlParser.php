<?php
pload('pYamlInline');
pload('packfire.collection.pMap');
pload('pYamlReference');
pload('pYamlPart');

/**
 * Contains constants that identify parts of the document
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.yaml
 * @since 1.0-sofia
 */
class pYamlParser {
    
    /**
     * The reader to read in data from the input stream
     * @var pInputStreamReader
     * @since 1.0-sofia
     */
    private $read;
    
    /**
     * The previous line
     * @var string
     * @since 1.0-sofia
     */
    private $postline = null;
    
    /**
     * A hash map containing the references defined in the YAML document
     * @var pMap
     * @since 1.0-sofia
     */
    private $reference;
    
    /**
     * Create the parser based on the pInputStreamReader
     * @param pInputStreamReader $reader The reader that helps to read the data
     *                                   from the YAML stream.
     * @since 1.0-sofia
     */
    public function __construct($reader){
        $this->read = $reader;
    }
    
    /**
     * Get the references for the document
     * 
     * @return pMap Returns a pMap containing the $referenceName => $reference
     *              combination. If parsing of a document has yet started, the
     *              method returns null instead. $reference is an instance of
     *              pYamlReference.
     * @since 1.0-sofia
     */
    public function reference(){
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
    public function parse(){
        $result = array();
        $this->reference = new pMap(); // reset the reference
                                       //does not support cross document
        // the overall Map
        while($this->read->stream()->tell() < $this->read->stream()->length()){
            $data = $this->parseBlock();
            $items = count($data);
            foreach($data as $k => $v){
                // the reason why we used this instead of
                // array_merge or += because of the numeric key thingy
                if(is_int($k) && $k < $items){
                    $result[] = $v;
                }else{
                    $result[$k] = $v;
                }
            }
            
            if(pYamlPart::DOC_END == $this->postline){
                // document ended
                break;
            }
        }
        return $result;
    }
    
    /**
     * Parse the reader till the start of the YAML document, which is
     * the three hypens (---). 
     * @since 1.0-sofia
     */
    public function findDocumentStart(){
        $this->read->until(pYamlPart::DOC_START);
        
        // Some methods are capable of parsing values
        // with closures like the following: 
       
//        $this->read->stream()->seek(function($x){
//            return $x->tell() + 3;
//        });
    }
    
    /**
     * Prepare a line by normalizing newline characters and stripping comments
     * @param string $line The line to prepare
     * @return string Returns the prepared line
     * @since 1.0-sofia
     */
    private function prepareLine($line){
        $line = str_replace(array("\r\n", "\r"), array("\n", "\n"), $line);
        $line = pYamlValue::stripComment($line);
        return $line;
    }
    
    /**
     * Read in the next line for process
     * @param string $line (optional) The line to use
     * @return array Returns an array containing [$line, $trimmed, $indentation]
     * @since 1.0-sofia
     */
    private function nextLine($line = null){
        if($line === null){
            if($this->postline !== null){
                $line = $this->postline;
                $this->postline = null;
            }else{
                $line = $this->read->line();
            }
        }
        if(substr(trim($line), 0, 1) == '#'){
            return $this->nextLine();
        }else{
            $line = $this->prepareLine($line);
            $trimmed = trim($line);
            $indentation = 0;
            if(strlen($trimmed) > 0){
                $indentation = strpos($line, $trimmed);
            }
            return array($line, $trimmed, $indentation);
        }
    }
    
    /**
     * Parse a line for key: value
     * @param string $line The line to parse
     * @return array Returns an array containing [$key, $value]
     * @since 1.0-sofia
     */
    public function parseKeyValue($line){
        $position = 0;
        $key = pYamlInline::load($line)
                ->parseScalar($position,
                        array(pYamlPart::KEY_VALUE_SEPARATOR), false);
        $after = $position;
        if($after >= strlen($line)){
            $value = null;
        }else{
            $value = trim(substr($line, $after));
            if($key[0] == '[' || $key[0] == '{'){
                $key = $key . pYamlPart::KEY_VALUE_SEPARATOR . $value;
                $value = null;
            }
        }
        return array($key, $value);
    }
    
    /**
     * Parse the following embed block.
     * @param integer $minLevel (optional) The block's indentation level
     * @return array Returns the array of data parsed
     * @since 1.0-sofia
     */
    public function parseBlock($minLevel = 0){
        $result = array();
        $trimmed = '';
        while($trimmed == '' && $this->read->stream()->tell() < $this->read->stream()->length()){
            list($line, $trimmed, $indentation) = $this->nextLine();
            if(!$line){
                continue;
            }
            if($minLevel > $indentation){
                break;
            }
            if(pYamlPart::DOC_END == $trimmed){
                // document ended
                $this->postline = $line;
                break;
            }
            if(strlen($trimmed) > 0){
                if($trimmed[0] == '{'){
                    $this->postline = $line;
                    $result = trim($this->fetchBlock($indentation));
                    $result = pYamlInline::load($result)->parseMap();
                }elseif($trimmed[0] == '['){
                    $this->postline = $line;
                    $result = trim($this->fetchBlock($indentation));
                    $result = pYamlInline::load($result)->parseSequence();
                }elseif(substr(ltrim($line), 0, 2) == pYamlPart::SEQUENCE_ITEM_BULLET
                        || ltrim($line) == pYamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE){
                    $this->postline = $line;
                    $result = $this->parseSequenceItems($indentation);
                }else{
                    if($this->hasKeyValueLine($trimmed)){
                        $this->postline = $line;
                        $result = $this->parseMapItems($indentation);
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * Check if a line has a "key: value" sequence
     * @param string $line The line to check
     * @return boolean Returns true if a key value sequence is found, false
     *                 otherwise. 
     */
    private function hasKeyValueLine($line){
        list($key, ) = $this->parseKeyValue($line);
        return $key !== $line;
    }
    
    /**
     * Fetch the full value by checking subsequent lines too
     * @param string $value What we have for the value so far...
     * @param string $minLevel The indentation level
     * @return string|array Returns the value parsed.
     * @since 1.0-sofia
     */
    private function fetchFullValue($value, $minLevel){
        $result = $value;
        $trimmed = trim($value);
        if(strlen($trimmed) > 0){
            $lastchar = substr($trimmed, -1);
            switch($trimmed[0]){
                case '{':
                    if($lastchar != '}'){
                        $this->postline = str_repeat(' ', $minLevel) . $value;
                        $value = trim($this->fetchBlock($minLevel));
                    }
                    $result = pYamlInline::load($value)->parseMap();
                    break;
                case '[':
                    if($lastchar != ']'){
                        $this->postline = str_repeat(' ', $minLevel) . $value;
                        $value = trim($this->fetchBlock($minLevel));
                    }
                    $result = pYamlInline::load($value)->parseSequence();
                    break;
                case '|': // newlines preserved literal blocks
                    $result = trim($this->fetchBlock($minLevel));
                    break;
                case '>': // folded literal block
                    $value = trim($this->fetchBlock($minLevel));
                    $value = preg_replace(array('`\n\s+([^\s]+)`', '`([^\n]+)\n([^\n]+)`'),
                            array("\n".'$1', '$1 $2'), $value);
                    $result = str_replace("\n\n", "\n", $value);
                    break;
                case '*': // refer to reference
                    $referenceName = substr($trimmed, 1);
                    if($this->reference->keyExists($referenceName)){
                        $result = $this->reference->get($referenceName);
                    }
                    break;
                case '&': // reference creation
                    $referenceName = substr($trimmed, 1);
                    $value = new pYamlReference($this->parseBlock($minLevel));
                    $this->reference[$referenceName] = $value;
                    $result = $value;
                    break;
                default: // normal scalar value
                    $result = pYamlInline::load($trimmed)->parseScalar();
                    break;
            }
        }
        return $result;
    }
    
    /**
     * Parse the sequence block
     * @param string $minLevel The indentation level for this block
     * @return array Returns an array of items from parsing the sequence block.
     * @since 1.0-sofia
     */
    private function parseSequenceItems($minLevel){
        $result = array();
        $indentation = $minLevel;
        $line = null;
        while($minLevel <= $indentation){
            list($line, $trimmed, $indentation) = $this->nextLine();
            if($trimmed == pYamlPart::DOC_END){
                $this->postline = $line;
                break;
            }
            
            if($trimmed == ''){
                break;
            }
            
            if($minLevel <= $indentation
                    && (substr(ltrim($line), 0, 2) == pYamlPart::SEQUENCE_ITEM_BULLET
                    || ltrim($line) == pYamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE)){
                
                list($key, $value) = $this->parseKeyValue(substr($trimmed, 2));
                if($key == pYamlValue::stripQuote(substr($trimmed, 2))
                        && $key !== '' && $value === null){
                    // - value
                    $result[] = $this->fetchFullValue($key, $minLevel + 2);
                }elseif('' === $key && $value === null){
                    // - 
                    //   value
                    $result[] = $this->parseBlock($minLevel + 2);
                }else{
                    // - key: value
                    if($value === null){
                        $lastLine = str_repeat(' ', $indentation + 2) . $key . ': ';
                    }else{
                        $lastLine = str_repeat(' ', $indentation + 2) . $key . ': ' . $value;
                    }
                    $this->postline = $lastLine;
                    $result[] = $this->parseMapItems($indentation + 2);
                }
            }
        }
        
        $this->postline = $line;
        return $result;
    }
    
    /**
     * Parse the map block
     * @param integer $minLevel The indentation level for the map block.
     * @return array Returns an associative array containing key value data
     *               parsed from the map block.
     * @since 1.0-sofia
     */
    private function parseMapItems($minLevel){
        $result = array();
        $indentation = $minLevel;
        $line = null;
        while($minLevel <= $indentation){
            list($line, $trimmed, $indentation) = $this->nextLine();
            if($trimmed == pYamlPart::DOC_END){
                $this->postline = $line;
                break;
            }
            
            if($trimmed == ''){
                break;
            }
            
            if($minLevel <= $indentation){
                list($key, $value) = $this->parseKeyValue($trimmed);
                if($value === null){
                    // value
                    // on its own...
                    $value = $this->parseBlock($minLevel + 2);
                }else{
                    // we've got a value and key!
                    $value = $this->fetchFullValue(trim($value), $minLevel + 2);
                }
                $result[$key] = $value;
            }
        }
        $this->postline = $line;
        return $result;
    }
    
    /**
     * Fetch a block based on the indentation
     * @param integer $minIndentation The block's indentation level
     * @return string The block's data.
     * @since 1.0-sofia
     */
    private function fetchBlock($minIndentation){
        $text = '';
        $indentation = $minIndentation;
        
        while($minIndentation <= $indentation
                && $this->read->stream()->tell() < $this->read->stream()->length()){
            
            list($line, $trimmed, $indentation) = $this->nextLine();
            
            if($trimmed == ''){
                $indentation = $minIndentation;
            }
            if($minIndentation <= $indentation){
                $text .= $line;
            }
        }
        if($minIndentation > $indentation){
            $this->postline = $line;
        }
        
        return $text;
    }
    
}