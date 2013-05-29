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

use Packfire\Core\IObservable;

/**
 * Concrete Observable implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core
 * @since 1.0-elenor
 */
class Observable implements IObservable
{
    /**
     * The observers observing this Observable
     * @var array
     * @since 1.0-sofia
     */
    protected $observers = array();

    /**
     * Attach an observer to the observable to start observing
     * @param IObserver $observer The observer to be watching this class
     * @since 1.0-elenor
     */
    public function attach($observer)
    {
        if (!in_array($observer, $this->observers)) {
            $this->observers[] = $observer;
        }
    }

    /**
     * Detach an observer from the observable to stop observing
     * @param IObserver $observer The observer already watching this class
     * @since 1.0-elenor
     */
    public function detach($observer)
    {
        $keys = array_keys($this->observers, $observer, true);
        foreach ($keys as $key) {
            unset($this->observers[$key]);
        }
    }

    /**
     * Notify all observers
     * @param mixed $arg (optional) The additional information about this
     *              notification to send to the observers.
     * @since 1.0-elenor
     */
    public function notify($arg = null)
    {
        /* @var $observer IObserver */
        if (func_num_args() == 1) {
            foreach ($this->observers as $observer) {
                $observer->updated($this, $arg);
            }
        } else {
            foreach ($this->observers as $observer) {
                $observer->updated($this);
            }
        }
    }

}
