<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Event;

use Packfire\Core\Observable;

/**
 * An event's observable handler implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Event
 * @since 1.0-elenor
 */
class ObservableEvent extends Observable
{
    /**
     * The event listener holding this event handler
     * @var object
     * @since 1.0-elenor
     */
    private $listener;

    /**
     * Create a new ObservableEvent object
     * @param object $listener The event listener holding this handler
     * @since 1.0-elenor
     */
    public function __construct($listener)
    {
        $this->listener = $listener;
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
                $observer->updated($this->listener, $arg);
            }
        } else {
            foreach ($this->observers as $observer) {
                $observer->updated($this->listener);
            }
        }
    }

}
