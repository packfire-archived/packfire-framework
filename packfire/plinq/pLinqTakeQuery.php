<?php
pload('ILinqQuery');

/**
 * pLinqTakeQuery Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.linq
 * @since 1.0-sofia
 */
class pLinqTakeQuery implements ILinqQuery {
    
    private $count;
    
    public function __construct($count){
        $this->count = $count;
    }
    
    /**
     *
     * @param pList $collection
     * @return pList 
     */
    public function run($collection) {
        return array_slice($collection, 0, $this->count);
    }
    
}