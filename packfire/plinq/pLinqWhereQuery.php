<?php
pload('pLinqWorkerQuery');

/**
 * pLinqWhereQuery Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since version-created
 */
class pLinqWhereQuery extends pLinqWorkerQuery {
    
    public function run($collection) {
        $result = array();
        $worker = $this->worker();
        foreach($collection as $element){
            if($worker($element)){
                $result[] = $element;
            }
        }
        return $result;
    }

}