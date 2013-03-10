<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Debugger;

use Packfire\DateTime\DateTime;
use Packfire\IO\File\Path;
use Packfire\FuelBlade\IConsumer;

/**
 * The debugger to help you debug in your application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Debugger
 * @since 1.0-sofia
 */
class Debugger implements IConsumer {
    
    /**
     * The output method of the debugger
     * @var \Packfire\Debugger\IOutput
     * @since 2.1.0
     */
    private $output;
    
    /**
     * State whether the debugger is enabled or not.
     * By default, the debugger is enabled.
     * @var boolean
     * @since 1.0-sofia
     */
    protected $enabled = true;
    
    /**
     * Create a new Debugger object
     * @since 1.0-sofia
     */
    public function __construct(){
        
    }
    
    /**
     * Check whether the debugger is enabled or not
     * @param boolean $enable (optional) Set whether the debugger is enabled or not.
     *                  True to enable the debugger and false to disable it.
     * @return boolean Returns true if the debugger is enabled, false otherwise.
     * @since 1.0-sofia
     */
    public function enabled($enable = null){
        if(func_num_args() == 1){
            $this->enabled = $enable;
        }
        return $this->enabled;
    }

    /**
     * Write a variable dump to the debugger log
     * @param mixed $value The variable to dump
     * @since 1.0-sofia 
     */
    public function dump($value){
        if($this->enabled){
            if(func_num_args() > 1){
                $args = func_get_args();
                foreach($args as $value){
                    $this->dump($value);
                }
            }else{
                $output = var_export($value, true);
                $dbts = debug_backtrace();
                $dbt = reset($dbts);
                $where = sprintf('%s:%d', Path::baseName($dbt['file']),
                        $dbt['line']);
                $this->output->write($output, $where, __FUNCTION__);
            }
        }
    }
    
    /**
     * Write a log message
     * @param string $message The log message to be written
     * @since 1.0-sofia 
     */
    public function log($message){
        if($this->enabled){
            $this->output->write($message);
        }
    }
    
    /**
     * Log an exception's details
     * @param Exception $exception The exception to be logged
     * @since 1.0-sofia
     */
    public function exception($exception){
        if($this->enabled){
            $where = sprintf('%s:%d',
                    Path::baseName($exception->getFile()),
                    $exception->getLine());
            $message = sprintf('Error %s: %s', $exception->getCode(),
                    $exception->getMessage());
            $this->output->write($message, $where, __FUNCTION__);
        }
    }
    
    /**
     * Do a time check log.
     * Time taken from the application load to reach the time check will be 
     * shown on the log
     * @since 1.0-sofia 
     */
    public function timeCheck(){
        if($this->enabled){
            $dbts = debug_backtrace();
            $dbt = reset($dbts);
            $message = sprintf(
                    'Time taken from application loaded to reach %s line %s',
                    $dbt['file'], $dbt['line']);
            $this->output->write($message,
                    (DateTime::microtime() - __PACKFIRE_START__) . 's',
                    __FUNCTION__);
        }
    }
    
    /**
     * Log the SQL query performed.
     * @param string $sql The query string.
     * @param string $type (optional) Defaults to 'query'. Can be 'query'
     *               or 'prepared'. 
     * @since 1.0-sofia
     */
    public function query($sql, $type = 'query'){
        if($this->enabled){
            $dbts = debug_backtrace();
            $dbt = $dbts[1];
            if(!array_key_exists('file', $dbt)){
                $dbt['file'] = '{unknown}';
            }
            if(!array_key_exists('line', $dbt)){
                $dbt['line'] = '{0}';
            }
            $where = sprintf('%s:%d', Path::baseName($dbt['file']),
                    $dbt['line']);
            $this->output->write($sql, $where, $type);
        }
    }
    
    /**
     * Perform output when the debugger is destroyed, supposedly application
     * end execution.
     * @internal
     * @ignore 
     */
    public function __destruct(){
        if($this->enabled && __ENVIRONMENT__ != 'test'){
            $this->timeCheck();
            $this->output->output();
        }
    }
    
    public function __invoke($container) {
        $this->output = $container['debugger.output'];
        $this->enabled = $container['config']->get('app', 'debug');
        return $this;
    }
    
}