<?php
pload('pLinqOrderByQuery');

/**
 * LINQ Order Then By Query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
class pLinqThenByQuery extends pLinqOrderByQuery {
    
    private $previousCallback;
    
    public function __construct($worker, $previousCallback, $descending = false){
        parent::__construct($worker, $descending);
        $this->previousCallback = $previousCallback;
    }
    
    public function compare($a, $b){
        $result = $this->previousCallback($a, $b);
        if($result != 0){
            return $result;
        }
        return parent::compare($a, $b);
    }
    
}