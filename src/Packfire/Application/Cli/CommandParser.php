<?php
namespace Packfire\Application\Cli;

pload('packfire.collection.pList');
pload('packfire.collection.pMap');

/**
 * CommandParser class
 * 
 * A parser that will process the command line arguments
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-sofia
 */
class CommandParser {
    
    /**
     * The result of the parsing
     * @var pMap
     * @since 1.0-sofia
     */
    private $result;
    
    /**
     * Create a new CommandParser object
     * @param array|pMap $arguments The argument to be parsed.
     * @since 1.0-sofia
     */
    public function __construct($arguments){
        $this->parse($arguments);
    }
    
    /**
     * Parses the command line into keys and result
     * @param array|pMap $arguments The arguments to be parsed
     * @since 1.0-sofia
     */
    private function parse($arguments){
        $this->result = new pMap();
        $lastKey = null;
        foreach($arguments as $idx => $arg){
            $equalsPos = strpos($arg, '=');
            if($equalsPos !== false){
                $key = substr($arg, 0, $equalsPos);
                $arg = substr($arg, $equalsPos + 1);
                $this->set($this->cleanKey($key), $arg);
                $lastKey = null;
            }elseif($this->isFlag($arg)){
                $arg = $this->cleanKey($arg);
                foreach(str_split($arg) as $argument){
                    $this->set($argument, true);
                }
                $lastKey = null;
            }elseif($this->isKey($arg)){
                $lastKey = $this->cleanKey($arg);
            }else{
                $this->set($lastKey ? $lastKey : $idx, $arg);
                $lastKey = null;
            }
        }
        if($lastKey){
            $this->set($lastKey, true);
        }
    }
    
    /**
     * Check if an argument is a switch key
     * @param string $part The argument part
     * @return boolean Returns true if the switch is a key, and false otherwise
     * @since 1.0-sofia
     */
    private function isFlag($part){
        $result = false;
        $len = strlen($part);
        if($len > 1){
            // if it is only -vfc not -v=5
            if($part[0] == '-' && $part[1] != '-' && strpos($part, '=') === false){
                $result = true;
            }elseif($part[0] == '/'){ // if only it is /a not /activate
                $result = $len == 2;
            }
        }
        return $result;
    }
    
    /**
     * Check if an part is an argument key
     * @param string $part The argument part
     * @return boolean Returns true if the switch is a key, and false otherwise
     * @since 1.0-sofia
     */
    private function isKey($part){
        $result = false;
        if(strlen($part) > 0){
            if (substr($part, 0 ,2) == '--' || $part[0] == '/'){
                $result = true;
            }
        }
        return $result;
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
            if ($firstChar == '-' || $firstChar == '/'){
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
     * Get the full result of the parser
     * @return pMap Returns the array containing the result
     * @since 1.0-sofia
     */
    public function result(){
        return $this->result;
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
                }else if($altResult != null){
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
        $keys = $this->result->keys();
        $result = $keys->contains($switch);
        if($alternate){
            $alternate = $this->cleanKey($alternate);
            $result = $result || $keys->contains($alternate);
        }
        return $result;
    }
    
}