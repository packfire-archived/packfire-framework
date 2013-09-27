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

use Packfire\Core\IObserver;

/**
 * Implementation for a handler that will run closure or callback observers
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Event
 * @since 1.0-elenor
 */
class EventObserver implements IObserver
{
    /**
     * The event handler that will receive the notification
     * @var Closure|callback
     * @since 1.0-elenor
     */
    private $handler;

    /**
     * Create a new EventObserver object
     * @param Closure|callback $handler The event handler that will receive the
     *              notification on the update.
     * @since 1.0-elenor
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Notify the observer that the observable has been updated
     * @param IOberservable|object $observable The object being observed
     * @param mixed                $arg        (optional) The additional event arguments.
     * @since 1.0-elenor
     */
    public function updated($observable, $arg = null)
    {
        // pass the arguments to the handler to handle.
        call_user_func_array($this->handler, func_get_args());
    }
}
