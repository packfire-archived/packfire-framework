<?php
pload('IObservable');

/**
 * pObservable class
 * 
 * Concrete Observable implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.core
 * @since 1.0-elenor
 */
class pObservable implements IObservable {
    
    /**
     * The observers observing this pObservable
     * @var array
     * @since 1.0-sofia
     */
    protected $observers = array();
    
    public function attach($observer) {
        if(!in_array($observer, $this->observers)){
            $this->observers[] = $observer;
        }
    }

    public function detach($observer) {
        if(!(is_array($observer) || $observer instanceof pList)){
            $observer = array($obserser);
        }
        $this->observers = array_diff($this->observers, $observer);
    }

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