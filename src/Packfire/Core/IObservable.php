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
interface IObservable
{
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
