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
use Packfire\Exception\MissingDependencyException;

//@codeCoverageIgnoreStart
if (!class_exists('ReflectionMethod')) {
    throw new MissingDependencyException('ActionInvoker requires Reflection to be enabled in order to work');
}
//@codeCoverageIgnoreEnd

/**
 * Invokes a callback with an associative argument array
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core
 * @since 1.1-sofia
 */
class ActionInvoker
{
    /**
     * The callback to be invoked
     * @var Closure|callback
     * @since 1.1-sofia
     */
    protected $callback;

    /**
     * Create a new ActionInvoker object
     * @param Closure|callback $callback The callback to be invoked
     * @since 1.1-sofia
     */
    public function __construct($callback)
    {
        if (is_string($callback)) {
            // detect if callback is
            $pos = strpos($callback, '::');
            if ($pos !== false) {
                $callback = array(
                    substr($callback, 0, $pos),
                    substr($callback, $pos + 2)
                );
            }
        }
        $this->callback = $callback;
    }

    /**
     * Invoke the action with associative array of arguments
     * @param  Map|array $args The arguments to be passed in.
     * @return mixed     Returns whatever the callback returns
     * @since 1.1-sofia
     */
    public function invoke($params)
    {
        if ($params instanceof Map) {
            $params = $params->toArray();
        }
        $invokeParams = array();
        if (is_array($this->callback)) {
            $reflection = new \ReflectionMethod($this->callback[0], $this->callback[1]);
            if ($reflection->isStatic()) {
                $invokeParams[] = null;
            } else {
                $invokeParams[] = $this->callback[0];
            }
        } else {
            $reflection = new \ReflectionFunction($this->callback);
        }

        $pass = array();
        foreach ($reflection->getParameters() as $param) {
            /* @var $param ReflectionParameter */
            if (isset($params[$param->getName()])) {
                $pass[] = $params[$param->getName()];
            } elseif ($param->isOptional()) {
                try {
                    $pass[] = $param->getDefaultValue();
                } catch (\ReflectionException $ex) {

                }
            }
        }
        $invokeParams[] = $pass;

        return call_user_func_array(array($reflection, 'invokeArgs'), $invokeParams);
    }

}
