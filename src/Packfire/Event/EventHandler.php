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

use Packfire\Event\IEventHandler;
use Packfire\Event\ObservableEvent;
use Packfire\Event\EventObserver;

/**
 * Implementation of the event handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.event
 * @since 1.0-elenor
 */
class EventHandler implements IEventHandler
{
    /**
     * The events to the event listener
     * @var array
     * @since 1.0-elenor
     */
    private $events = array();

    /**
     * The event listener holding this event handler
     * @var object
     * @since 1.0-elenor
     */
    private $listener;

    /**
     * Create a new EventHandler object
     * @param object $listener The event listener holding this handler
     * @since 1.0-elenor
     */
    public function __construct($listener)
    {
        $this->listener = $listener;
    }

    /**
     * Bind an event listener to an event of the class
     * @param string                     $event    The name of the event
     * @param IObserver|Closure|callback $observer The function, method or
     *              observer to listen to this event
     * @since 1.0-elenor
     */
    public function on($event, $observer)
    {
        if (!array_key_exists($event, $this->events)) {
            $this->events[$event] = new ObservableEvent($this->listener);
        }
        if (is_callable($observer)) {
            $observer = new EventObserver($observer);
        }
        $this->events[$event]->attach($observer);
    }

    /**
     * Trigger an event in this handler
     * @param string $event The name of the event to trigger
     * @param mixed  $args  (optional) Additional information to provide to the
     *              observers.
     * @since 1.0-elenor
     */
    public function trigger($event, $args = null)
    {
        if (array_key_exists($event, $this->events)) {
            if (func_num_args() == 2) {
                $this->events[$event]->notify($args);
            } else {
                $this->events[$event]->notify();
            }
        }
    }
}
