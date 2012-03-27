<?php
pload('packfire.collection.pList');
pload('packfire.collection.pMap');
pload('packfire.collection.pKeyValuePair');
pload('pLinqWorkerQuery');

/**
 * pLinqGroupByQuery Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
class pLinqGroupByQuery extends pLinqWorkerQuery {
    
    public function run($collection) {
        $grouping = array();
        $worker = $this->worker();
        $matrix = new pMap();
        
        foreach($collection as $element){
            $key = $worker($element);
            if(!$matrix->keyExists($key)){
                $matrix->add($key, new pList());
            }
            $matrix->get($key)->add($element);
        }
        
        foreach($matrix as $key => $value){
            $grouping[] = new pKeyValuePair($key, $value);
        }
        return $grouping;
    }
    
}