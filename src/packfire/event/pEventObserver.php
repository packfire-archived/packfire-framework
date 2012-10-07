<?php
pload('packfire.core.IObserver');

/**
 * pEventObserver class
 * 
 * Implementation for a handler that will run closure or callback observers
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.event
 * @since 1.0-elenor
 */
class pEventObserver implements IObserver {
    
    /**
     * The event handler that will receive the notification
     * @var Closure|callback
     * @since 1.0-elenor
     */
    private $handler;
    
    /**
     * Create a new pEventObserver class
     * @param Closure|callback $handler The event handler that will receive the
     *              notification on the update.
     * @since 1.0-elenor
     */
    public function __construct($handler){
        $this->handler = $handler;
    }
    
    /**
     * Notify the observer that the observable has been updated
     * @param IOberservable|object $observable The object being observed
     * @param mixed $arg (optional) The additional event arguments.
     * @since 1.0-elenor
     */
    public function updated($observable, $arg = null) {
        // pass the arguments to the handler to handle.
        call_user_func_array($this->handler, func_get_args());
    }

}