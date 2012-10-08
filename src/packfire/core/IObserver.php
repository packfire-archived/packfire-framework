<?php
namespace Packfire\Core;

/**
 * IObserver interface 
 * 
 * Provides interface for Observer patten
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core
 * @since 1.0-elenor
 */
interface IObserver {
    
    /**
     * Notify the observer that the observable has been updated
     * @param IObservable $observable The observable that called this observer
     * @param mixed $arg (optional) The additional information about this notification.
     * @since 1.0-elenor
     */
    public function updated($observable, $arg = null);
    
}