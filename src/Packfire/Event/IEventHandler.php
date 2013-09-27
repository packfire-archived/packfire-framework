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

use Packfire\Event\IEventWatchable;

/**
 * Abstraction for an Event Handler implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Event
 * @since 1.0-elenor
 */
interface IEventHandler extends IEventWatchable
{
    /**
     * Trigger an event in this handler
     * @param string $event The name of the event to trigger
     * @param mixed  $args  (optional) Additional information to provide to the
     *              observers.
     * @since 1.0-elenor
     */
    public function trigger($event, $args = null);
}
