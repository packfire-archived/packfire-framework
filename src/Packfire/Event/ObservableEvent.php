<?php
namespace Packfire\Event;

use Packfire\Core\Observable;

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
class ObservableEvent extends Observable {
    
    /**
     * The event listener holding this event handler
     * @var object
     * @since 1.0-elenor
     */
    private $listener;
    
    /**
     * Create a new pObservableEvent object
     * @param object $listener The event listener holding this handler
     * @since 1.0-elenor
     */
    public function __construct($listener){
        $this->listener = $listener;
    }
    
    /**
     * Notify all observers
     * @param mixed $arg (optional) The additional information about this
     *              notification to send to the observers.
     * @since 1.0-elenor 
     */
    public function notify($arg = null) {
        /* @var $observer IObserver */
        if(func_num_args() == 1){
            foreach($this->observers as $observer){
                $observer->updated($this->listener, $arg);
            }
        }else{
            foreach($this->observers as $observer){
                $observer->updated($this->listener);
            }
        }
    }
    
}