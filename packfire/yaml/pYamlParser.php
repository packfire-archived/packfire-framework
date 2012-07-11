<?php
pload('pYamlInline');
pload('packfire.collection.pMap');
pload('pYamlReference');
pload('pYamlPart');
pload('packfire.text.pNewline');

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
     * @var pStreamReader
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
     * @var pMap
     * @since 1.0-sofia
     */
    private $reference;
    
    /**
     * Create the parser based on the pInputStreamReader
     * @param pStreamReader $reader The reader that helps to read the data
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
        $this->reference = new pMap(); // reset the reference
                                       //does not support cross document
        // the overall Map
        
        $result = $this->parseBlock();
        return $result;
    }
    
    /**
     * Parse the reader till the start of the YAML document, which is
     * the three hypens (---). 
     * @since 1.0-sofia
     */
    public function findDocumentStart(){
        $this->read->until(pYamlPart::DOC_START);
    }
    
    /**
     * Read in the next line for process
     * @since 1.0-sofia
     */
    private function nextLine(){
        $this->line = '';
        $this->trimmedLine = '';
        $this->indentation = 0;
        if($this->read->hasMore()){
            $this->line = pYamlValue::stripComment($this->read->line());
            if($this->line){
                $this->trimmedLine = trim($this->line);
                if($this->trimmedLine){
                    $this->indentation = strpos($this->line, $this->trimmedLine);
                }
            }
            return true;
        }else{
            return null;
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
        $key = pYamlInline::load($line)->parseScalar($position,
                        array(pYamlPart::KEY_VALUE_SEPARATOR), false);
        $after = $position;
        if($after >= strlen($line)){
            $value = null;
        }else{
            $value = trim(substr($line, $after));
            if($key[0] == '[' || $key[0] == '{'){
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
    public function parseBlock(){
        $this->nextLine();
        $minLevel = $this->indentation;
        $result = array();
        while($minLevel <= $this->indentation){
            if(pYamlPart::DOC_END == $this->trimmedLine){
                break;
            }
            
            $doNext = true;
            if($this->trimmedLine){
                if($this->trimmedLine[0] == '{'){
                    $result = pYamlInline::load(trim($this->fetchBlock()))->parseMap();
                }elseif($this->trimmedLine[0] == '['){
                    $result = pYamlInline::load(trim($this->fetchBlock()))->parseSequence();
                }elseif(substr($this->trimmedLine, 0, 2) == pYamlPart::SEQUENCE_ITEM_BULLET
                        || $this->trimmedLine == pYamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE){
                    $result = $this->parseSequenceItems();
                    $doNext = false;
                }else{
                    if($this->hasKeyValueLine($this->trimmedLine)){
                        $result = $this->parseMapItems();
                        $doNext = false;
                    }
                }
            }
            if($doNext){
                $next = $this->nextLine();
                if(!$next){
                    break;
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
        return $key != $line;
    }
    
    /**
     * Fetch the full value by checking subsequent lines too
     * @param string $value What we have for the value so far...
     * @param string $minLevel The indentation level
     * @return string|array Returns the value parsed.
     * @since 1.0-sofia
     */
    private function fetchFullValue(){
        $result = $this->line;
        if($this->trimmedLine){
            $lastchar = substr($this->trimmedLine, -1);
            switch($this->trimmedLine[0]){
                case '{':
                    if($lastchar != '}'){
                        $result = trim($this->fetchBlock());
                    }
                    $result = pYamlInline::load($result)->parseMap();
                    break;
                case '[':
                    if($lastchar != ']'){
                        $result = trim($this->fetchBlock());
                    }
                    $result = pYamlInline::load($result)->parseSequence();
                    break;
                case '|': // newlines preserved literal blocks
                    $result = trim($this->fetchBlock());
                    break;
                case '>': // folded literal block
                    $result = trim($this->fetchBlock());
                    $result = preg_replace(array('`\n\s+([^\s]+)`', '`([^\n]+)\n([^\n]+)`'),
                            array("\n".'$1', '$1 $2'), $result);
                    $result = str_replace("\n\n", "\n", $result);
                    break;
                case '*': // refer to reference
                    $referenceName = substr($this->trimmedLine, 1);
                    if($this->reference->keyExists($referenceName)){
                        $result = $this->reference->get($referenceName);
                    }
                    break;
                case '&': // reference creation
                    $referenceName = substr($this->trimmedLine, 1);
                    $result= new pYamlReference($this->parseBlock());
                    $this->reference[$referenceName] = $result;
                    break;
                default: // normal scalar value
                    $result = pYamlInline::load($this->trimmedLine)->parseScalar();
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
    private function parseSequenceItems(){
        $result = array();
        
        $minLevel = $this->indentation;
        while($minLevel <= $this->indentation){
            if(pYamlPart::DOC_END == $this->trimmedLine){
                break;
            }
            
            if($this->trimmedLine){
                $bulletCheck = substr($this->trimmedLine, 0, 2);
                if(($bulletCheck == pYamlPart::SEQUENCE_ITEM_BULLET
                        || ltrim($this->line) == pYamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE)){

                    $lineValue = substr($this->trimmedLine, 2);
                    $cleanLineValue = pYamlValue::stripQuote($lineValue);
                    list($key, $value) = $this->parseKeyValue($cleanLineValue);

                    if($key == $cleanLineValue && '' !== $key && null === $value){
                        // - value
                        $this->line = str_repeat(' ', $this->indentation) . $lineValue;
                        $this->trimmedLine = $lineValue;
                        $result[] = $this->fetchFullValue();
                    }elseif('' === $key && $value === null){
                        // - 
                        //   value
                        $result[] = $this->parseBlock();
                    }else{
                        // - key: value
                        $this->line = str_repeat(' ', $this->indentation) . $lineValue;
                        $this->trimmedLine = $lineValue;
                        $result[] = $this->parseMapItems();
                    }
                }
            }
            $next = $this->nextLine();
            if(!$next){
                break;
            }
        }
        return $result;
    }
    
    /**
     * Parse the map block
     * @param integer $minLevel The indentation level for the map block.
     * @return array Returns an associative array containing key value data
     *               parsed from the map block.
     * @since 1.0-sofia
     */
    private function parseMapItems(){
        $result = array();
        $minLevel = $this->indentation;
        while($minLevel <= $this->indentation){
            if(pYamlPart::DOC_END == $this->trimmedLine){
                break;
            }
            if($this->trimmedLine){
                list($key, $value) = $this->parseKeyValue($this->trimmedLine);
                if($value === null){
                    // key is on its own...
                    $value = $this->parseBlock();
                }else{
                    // we've got a value and key!
                    $this->line = $value;
                    $this->trimmedLine = trim($value);
                    $this->intendation += 2;
                    $value = $this->fetchFullValue();
                }
                $result[$key] = $value;
            }
            $next = $this->nextLine();
            if(!$next){
                break;
            }
        }
        return $result;
    }
    
    /**
     * Fetch a block based on the indentation
     * @param integer $minIndentation The block's indentation level
     * @return string The block's data.
     * @since 1.0-sofia
     */
    private function fetchBlock(){
        $text = '';
        $minIndent = $this->indentation;
        
        while($minIndent <= $this->indentation){
            $text .= $this->line;
            $next = $this->nextLine();
            if(!$next){
                break;
            }
        }
        return $text;
    }
    
}