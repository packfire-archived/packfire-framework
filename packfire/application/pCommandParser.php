<?php
pload('packfire.collection.pList');
pload('packfire.collection.pMap');

/**
 * A parser that will process the command line
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
class pCommandParser {
    
    /**
     * The keys set in the argument line
     * @var pList
     * @since 1.0-sofia
     */
    private $keys;
    
    /**
     * The result of the parsing
     * @var pMap
     * @since 1.0-sofia
     */
    private $result;
    
    /**
     * Create a new pCommandParser object
     * @param string $line The argument line to be parsed
     * @since 1.0-sofia
     */
    public function __construct($line){
        $this->parse($line);
    }
    
    /**
     * Parses the command line into keys and result
     * @param string $line The argument line to be parsed
     * @since 1.0-sofia
     */
    private function parse($line){
        $this->result = new pMap();
        $this->keys = new pList();
        $position = 0;
        $length = strlen($line);
        $lastKey = null;
        $position = $this->parserFindNext($line, $position, '-');
        while($position < $length){
            $keyStart = $position;
            $startChar = substr($line, $position, 1);
            if($startChar == '"' || $startChar == '\''){
                ++$position;
                $keyEnd = $this->parserFindNext($line, $position, $startChar) + 1;
            }else if ($startChar == '-' || $startChar == '/'){
                $keyEnd = $this->parserFindNext($line, $position, array(' ', '='));
            }else{
                $keyEnd = $this->parserFindNext($line, $position, ' ');
            }
            if($position == $keyEnd){
                $keyEnd = $length;
            }
            $part = substr($line, $keyStart, $keyEnd - $keyStart);
            if($startChar == '-' || ($startChar == '/' && ($keyEnd - $keyStart) == 2)){ 
                $lastKey = $this->cleanKey($part);
                $this->keys->add($lastKey);
            }else{
                if($lastKey != null){
                    $this->set($lastKey, $part);
                }
            }
            $position = $keyEnd + 1;
        }
    }
    
    /**
     * Cleans a key and strip off the '--', '-' and '/' in front
     * @param string $key The key to be cleaned
     * @return string Returns the cleaned key
     * @since 1.0-sofia
     */
    private function cleanKey($key){
        if(substr($key, 0, 2) == '--'){
            $key = substr($key, 2);
        }else{
            $firstChar = substr($key, 0, 1);
            if ($firstChar == '-' && $firstChar == '/'){
                $key = substr($key, 1);
            }
        }
        return $key;
    }
    
    /**
     * Set a value to a key in the result
     * @param string $key The key
     * @param string $value The value
     * @since 1.0-sofia
     */
    private function set($key, $value){
        if($this->result->keyExists($key) 
                && ($this->result[$key] instanceof pList)){
            $this->result[$key]->add($value);
        }else if($this->result->keyExists($key)){
            $tmp = new pList();
            $tmp->add($this->result->get($key));
            $tmp->add($value);
            $this->result->add($key, $tmp);
        }else{
            $this->result->add($key, $value);
        }
    }
    
    /**
     * Look for the next occurance of a string
     * @param string $line The line to be processed
     * @param string $position The starting position to look for
     * @param pList|array|string $find The string to search for
     * @return integer The position returned. If not found, $position is returned.
     * @since 1.0-sofia
     */
    private function parserFindNext($line, $position, $find){
        $result = null;
        if(is_array($find) || $find instanceof pList){
            foreach($find as $findpart){
                $tmp = $this->parserFindNext($line, $position, $findpart);
                if($tmp != $position && (is_null($result) || $tmp < $result)){
                    $result = $tmp;
                }
            }
            if(is_null($result)){
                $result = $position;
            }
        }else{
            $result = $position;
            $tmp = strpos($line, $find, $position);
            if($tmp !== false){
                $result = $tmp;
            }
        }
        return $result;
    }
    
    /**
     * Get the full result of the parser
     * @return pMap Returns the array containing the result
     * @since 1.0-sofia
     */
    public function result(){
        $map = new pMap($this->result->toArray());
        $map->append($this->keys);
        return $map;
    }
    
    /**
     * Get the value(s) of a switch key
     * @param string $switch The switch key. Can be 's', '-s' or '/s'.
     * @param string $alternate The alternative switch key.
     *                      Can be '/switch' or '--switch'.
     * @return mixed Returns the value of the key
     * @since 1.0-sofia
     */
    public function getValue($switch, $alternate = null){
        $switch = $this->cleanKey($switch);
        $result = $this->result->get($switch);
        if($alternate){
            $alternate = $this->cleanKey($alternate);
            $altResult = $this->result->get($alternate);
            if($result == null){
                $result = $altResult;
            }else{
                if($result instanceof pList && $altResult instanceof pList){
                    $result->append($altResult);
                }else if($result instanceof pList){
                    $result->add($altResult);
                }else{
                    $result = new pList(array($result, $altResult));
                }
            }
        }
        return $result;
    }
    
    /**
     * Check if a switch is flagged
     * @param string $switch The switch key. Can be 's', '-s' or '/s'.
     * @param string $alternate The alternative switch key.
     *                      Can be '/switch' or '--switch'.
     * @return boolean Returns true if flagged, and false otherwise.
     * @since 1.0-sofia
     */
    public function isFlagged($switch, $alternate = null){
        $switch = $this->cleanKey($switch);
        $result = $this->keys->contains($switch);
        if($alternate){
            $alternate = $this->cleanKey($alternate);
            $result = $result || $this->keys->contains($alternate);
        }
        return $result;
    }
    
}