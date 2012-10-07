<?php

/**
 * IObservable interface
 * 
 * Provides interface for Observer patten
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.core
 * @since 1.0-elenor
 */
interface IObservable {
    
    /**
     * Attach an observer to the observable to start observing
     * @param IObserver $observer The observer to be watching this class
     * @since 1.0-elenor 
     */
    public function attach($observer);
    
    /**
     * Detach an observer from the observable to stop observing
     * @param IObserver $observer The observer already watching this class
     * @since 1.0-elenor 
     */
    public function detach($observer);
    
    /**
     * Notify all observers
     * @param mixed $arg (optional) The additional information about this
     *              notification to send to the observers.
     * @since 1.0-elenor 
     */
    public function notify($arg = null);
    
}