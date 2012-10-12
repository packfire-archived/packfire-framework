<?php
namespace Packfire\Session;

use Packfire\IoC\BucketUser;

/**
 * Messenger class
 *
 * Cross-class and controller session messenger. You can utilize this service
 * to provide messenging between classes and controllers.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session
 * @since 1.0-sofia
 */
class Messenger extends BucketUser {

    /**
     * Create a new Messenger object
     * @since 1.0-sofia
     */
    public function __construct(){

    }

    /**
     * Get the session bucket instance
     * @return SessionBucket Returns the session bucket instance
     * @since 1.0-sofia
     */
    private function session(){
        $bucket = $this->service('session')->bucket('Messenger');
        return $bucket;
    }

    /**
     * Build the session key for the name and recepient
     * @param string $name Name of the message
     * @param string $recepient (optional) The intended recepient.
     * @return string Returns the session key
     * @since 1.0-sofia
     */
    private function buildKey($name, $recepient = null){
        if(null == $recepient){
            $recepient = '{global}';
        }
        return '$'. $recepient . '/' . $name;
    }

    /**
     * Send a message to the recepient(s)
     * @param string $name Name of the message
     * @param ArrayList|array|string $recepient (optional) The recepient(s) to send to.
     *          If not set, message will be sent to the global scope.
     * @param mixed $message (optional) The message content. If not set, a
     *          message flag is set instead.
     * @since 1.0-sofia
     */
    public function send($name, $recepient = null, $message = true){
        if(is_array($recepient) || $recepient instanceof ArrayList){
            foreach($recepient as $to){
                $this->send($name, $to, $message);
            }
        }else{
            $this->session()->set($this->buildKey($name, $recepient), $message);
        }
    }

    /**
     * Check if there is a message
     * @param string $name The name of the message to check
     * @param string $recepient (optional) The recepient of the message. If not set,
     *                      the message will be checked in the global scope.
     * @return boolean Returns true if the message exists, false otherwise.
     * @since 1.0-sofia
     */
    public function check($name, $recepient = null){
        if(func_num_args() == 1){
            $traces = debug_backtrace();
            $trace = next($traces);
            $recepient = (array_key_exists('class', $trace) ?
                    $trace['class'] . ':' : '') . $trace['function'];
        }
        return $this->session()->has($this->buildKey($name, $recepient));
    }

    /**
     * Read the message content. Message will be removed after read.
     * @param string $name The name of the message to check
     * @param string $recepient (optional) The recepient of the message. If not set,
     *                      the message will be checked in the global scope.
     * @return mixed Returns the message content if the message exists, or NULL
     *              if the message does not exists.
     * @since 1.0-sofia
     */
    public function read($name, $recepient = null){
        if(func_num_args() == 1){
            $traces = debug_backtrace();
            $trace = next($traces);
            $recepient = (array_key_exists('class', $trace) ?
                    $trace['class'] . ':' : '') . $trace['function'];
        }
        $key = $this->buildKey($name, $recepient);
        $content = $this->session()->get($key);
        $this->session()->remove($key);
        return $content;
    }

    /**
     * Clear all messenges
     * @since 1.0-sofia
     */
    public function clear(){
        $this->session()->clear();
    }

}