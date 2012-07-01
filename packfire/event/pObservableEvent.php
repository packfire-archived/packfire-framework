<?php
pload('packfire.core.pObservable');

/**
 * pObservableEvent class
 * 
 * An event's observable handler implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.event
 * @since 1.0-elenor
 */
class pObservableEvent extends pObservable {
    
    /**
     * The event listener holding this event handler
     * @var pEventListener
     * @since 1.0-elenor
     */
    private $event;
    
    /**
     * Create a new pObservableEvent object
     * @param pEventListener $event The event listener holding this handler
     * @since 1.0-elenor
     */
    public function __construct($event){
        $this->event = $event;
    }
    
    public function notify($arg = null) {
        /* @var $observer IObserver */
        if(func_num_args() == 1){
            foreach($this->observers as $observer){
                $observer->updated($this->event, $arg);
            }
        }else{
            foreach($this->observers as $observer){
                $observer->updated($this->event);
            }
        }
    }
    
}