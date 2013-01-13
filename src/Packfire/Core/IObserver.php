<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Core;

/**
 * Provides interface for Observer patten
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
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