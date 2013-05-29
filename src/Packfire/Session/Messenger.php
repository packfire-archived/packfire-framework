<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session;

use Packfire\Collection\ArrayList;
use Packfire\FuelBlade\IConsumer;

/**
 * Cross-class and controller session messenger. You can utilize this service
 * to provide messenging between classes and controllers.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Session
 * @since 1.0-sofia
 */
class Messenger implements IConsumer
{
    /**
     * The session bucket instance
     * @var \Packfire\Session\Bucket\ISessionBucket
     * @since 2.1.0
     */
    private $session;

    public function __invoke($container)
    {
        $this->session = $container['session']->bucket('Messenger');

        return $this;
    }

    /**
     * Build the session key for the name and recepient
     * @param  string $name      Name of the message
     * @param  string $recepient (optional) The intended recepient.
     * @return string Returns the session key
     * @since 1.0-sofia
     * @codeCoverageIgnore
     */
    private function buildKey($name, $recepient = null)
    {
        if (null == $recepient) {
            $recepient = '{global}';
        }

        return '$'. $recepient . '/' . $name;
    }

    /**
     * Send a message to the recepient(s)
     * @param string                 $name      Name of the message
     * @param ArrayList|array|string $recepient (optional) The recepient(s) to send to.
     *          If not set, message will be sent to the global scope.
     * @param mixed $message (optional) The message content. If not set, a
     *          message flag is set instead.
     * @since 1.0-sofia
     */
    public function send($name, $recepient = null, $message = true)
    {
        if (is_array($recepient) || $recepient instanceof ArrayList) {
            foreach ($recepient as $to) {
                $this->send($name, $to, $message);
            }
        } else {
            $this->session->set($this->buildKey($name, $recepient), $message);
        }
    }

    /**
     * Check if there is a message
     * @param string $name      The name of the message to check
     * @param string $recepient (optional) The recepient of the message. If not set,
     *                      the message will be checked in the global scope.
     * @return boolean Returns true if the message exists, false otherwise.
     * @since 1.0-sofia
     */
    public function check($name, $recepient = null)
    {
        if (func_num_args() == 1) {
            $traces = debug_backtrace();
            $trace = next($traces);
            $recepient = (array_key_exists('class', $trace) ?
                    $trace['class'] . ':' : '') . $trace['function'];
        }

        return $this->session->has($this->buildKey($name, $recepient));
    }

    /**
     * Read the message content. Message will be removed after read.
     * @param string $name      The name of the message to check
     * @param string $recepient (optional) The recepient of the message. If not set,
     *                      the message will be checked in the global scope.
     * @return mixed Returns the message content if the message exists, or NULL
     *              if the message does not exists.
     * @since 1.0-sofia
     */
    public function read($name, $recepient = null)
    {
        if (func_num_args() == 1) {
            $traces = debug_backtrace();
            $trace = next($traces);
            $recepient = (array_key_exists('class', $trace) ?
                    $trace['class'] . ':' : '') . $trace['function'];
        }
        $key = $this->buildKey($name, $recepient);
        $content = $this->session->get($key);
        $this->session->remove($key);

        return $content;
    }

    /**
     * Clear all messenges
     * @since 1.0-sofia
     */
    public function clear()
    {
        $this->session->clear();
    }

}
