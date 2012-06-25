<?php

/**
 * IEventHandler interface
 * 
 * Abstraction for an Event Handler implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.event
 * @since 1.0-elenor
 */
interface IEventHandler {
    
    /**
     * Bind an event listener to an event of the class
     * @param string $event The name of the event
     * @param IObserver|Closure|callback $listener The function, method or
     *              observer to listen to this event
     * @since 1.0-elenor
     */
    public function on($event, $listener);
    
    /**
     * Trigger an event in this handler
     * @param string $event The name of the event to trigger
     * @param mixed $args (optional) Additional information to provide to the
     *              observers.
     * @since 1.0-elenor
     */
    public function trigger($event, $args = null);
    
}