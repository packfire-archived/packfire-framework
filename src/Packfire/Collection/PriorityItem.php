<?php
namespace Packfire\Collection;

/**
 * An Item with Priority
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class PriorityItem {
    
    /**
     * The key of the priority item
     * @var double|integer
     * @since 1.0-sofia
     */
    private $key;
    
    /**
     * The item
     * @var mixed
     * @since 1.0-sofia 
     */
    private $item;
    
    /**
     * Create a new pPriorityItem object
     * @param integer|double $key The key of the item
     * @param mixed $item The item
     * @since 1.0-sofia
     */
    public function __construct($key, $item) {
        $this->key = $key;
        $this->item = $item;
    }
    
    /**
     * Get the item
     * @return mixed Returns the item
     * @since 1.0-sofia 
     */
    public function item(){
        return $this->item;
    }
    
    /**
     * Get or set the key of the item
     * @param integer|double $key (optional) Set the key of the item
     * @return integer|double Returns the key of the item
     * @since 1.0-sofia
     */
    public function key($key = null){
        if(func_num_args() == 1){
            $this->key = $key;
        }
        return $key;
    }
    
}