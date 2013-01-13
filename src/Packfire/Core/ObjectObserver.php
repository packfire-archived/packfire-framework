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

use Packfire\Event\IEventWatchable;
use Packfire\Event\EventHandler;

/**
 * Watches a model's properties and methods for updates and changes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core
 * @since 1.1-sofia
 */
class ObjectObserver implements IEventWatchable {
    
    /**
     * The event handler
     * @var EventHandler
     * @since 1.1-sofia
     */
    private $handler;
    
    /**
     * The model that we're watching closely....
     * @var object
     * @since 1.1-sofia
     */
    private $model;
    
    /**
     * Create a new ObjectObserver object
     * @param object $model The object to be watched
     * @since 1.1-sofia
     */
    public function __construct($model){
        $this->handler = new EventHandler($this);
        $this->model = $model;
    }
    
    /**
     * Get the model itself
     * @return object Returns the model watched by the watcher
     * @since 1.1-sofia
     */
    public function model(){
        return $this->model;
    }
    
    /**
     * Bind an event listener to an event of the class
     * @param string $event The name of the event
     * @param IObserver|Closure|callback $listener The function, method or
     *              observer to listen to this event
     * @since 1.1-sofia
     */
    public function on($event, $listener){
        $this->handler->on($event, $listener);
    }
    
    /**
     * Magic method for setting attributes
     * @param string $name
     * @param mixed $value
     * @internal
     * @ignore
     * @since 1.1-sofia
     */
    public function __set($name, $value){
        if($this->model->$name != $value){
            $this->model->$name = $value;
            $this->handler->trigger('change', array($name, $value));
        }
    }
    
    /**
     * Magic method for getting attributes
     * @param string $name
     * @return mixed
     * @internal
     * @ignore
     * @since 1.1-sofia
     */
    public function __get($name){
        return $this->model->$name;
    }
    
    /**
     * Magic method for checking if attribute is set
     * @param string $name
     * @return boolean
     * @internal
     * @ignore
     * @since 1.1-sofia
     */
    public function __isset($name){
        return isset($this->model->$name);
    }
    
    /**
     * Magic method for unsetting attributes
     * @param string $name
     * @internal
     * @ignore
     * @since 1.1-sofia
     */
    public function __unset($name){
        unset($this->model->$name);
        $this->handler->trigger('unset', $name);
    }
    
    /**
     * Magic method for calling the model's method
     * @param string $name
     * @param array $args
     * @return mixed
     * @internal
     * @ignore
     * @since 1.1-sofia
     */
    public function __call($name, $args){
        $this->handler->trigger('call', array($name, $args));
        return call_user_func_array(array($this->model, $name), $args);
    }
    
}