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

use Packfire\Collection\Map;
use Packfire\Exception\InvalidArgumentException;

/**
 * Provides functionality to run tasks when script ends at shutdown.
 *
 * @link http://www.github.com/packfire
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core
 * @since 2.0.3
 */
class ShutdownTaskManager {
 
    /**
     * A collection of the tasks
     * @var \Packfire\Collection\Map
     * @since 2.0.3
     */
    private $tasks;
    
    /**
     * Create a new ShutdownTaskManager object
     * @since 2.0.3
     */
    public function __construct(){
        $this->tasks = new Map();
        register_shutdown_function(array($this, 'doShutdown'));
    }
    
    /**
     * Add a task to run at shutdown
     * @param string $name The unique identifier of the task.
     *          Calling twice with the same identifier will make the second
     *          call override the first call.
     * @param Closure|callback $task The task to run at shutdown
     * @param mixed $arg1,... (optional) Optional parameters to pass into the shutdown handler
     * @throws InvalidArgumentException Thrown when $task is not callable.
     * @since 2.0.3
     */
    public function add($name, $task){
        $args = func_get_args();
        $name = array_shift($args);
        if(!is_callable($task)){
            throw new InvalidArgumentException('ShutdownTaskManager::add', 'task', 'closure or callback', $task);
        }
        $this->tasks->add($name, $args);
    }
    
    /**
     * Remove a task from the manager
     * @param string $name The identifier of the task to remove
     * @since 2.0.3
     */
    public function remove($name){
        $this->tasks->removeAt($name);
    }
    
    /**
     * Call all registered shutdown tasks.
     * Warning: Do not call this method as it is used as the callback for
     * register_shutdown_function to call on shutdown.
     * 
     * @since 2.0.3
     */
    public function doShutdown(){
        foreach($this->tasks as $arguments){
            $task = array_shift($arguments);
            call_user_func_array($task, $arguments);
        }
    }
    
}