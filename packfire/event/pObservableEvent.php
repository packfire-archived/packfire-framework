<?php
pload('packfire.core.pObservable');

/**
 * pObservableEvent class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.event
 * @since 1.0-elenor
 */
class pObservableEvent extends pObservable {
    
    public function notify($arg = null) {
        /* @var $observer IObserver */
        if(func_num_args() == 1){
            foreach($this->observers as $observer){
                $observer->updated($this, $arg);
            }
        }else{
            foreach($this->observers as $observer){
                $observer->updated($this);
            }
        }
    }
    
}