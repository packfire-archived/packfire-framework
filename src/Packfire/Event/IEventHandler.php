<?php
namespace Packfire\Event;

use Packfire\Event\IEventWatchable;

/**
 * IEventHandler interface
 * 
 * Abstraction for an Event Handler implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Event
 * @since 1.0-elenor
 */
interface IEventHandler extends IEventWatchable {
    
    /**
     * Trigger an event in this handler
     * @param string $event The name of the event to trigger
     * @param mixed $args (optional) Additional information to provide to the
     *              observers.
     * @since 1.0-elenor
     */
    public function trigger($event, $args = null);
    
}