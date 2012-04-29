<?php
pload('packfire.ioc.pBucketUser');
pload('pConsoleDebugOutput');

/**
 * The debugger to help you debug in your application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger
 * @since 1.0-sofia
 */
class pDebugger extends pBucketUser {
    
    /**
     * State whether the debugger is enabled or not.
     * By default, the debugger is enabled.
     * @var boolean
     * @since 1.0-sofia
     */
    private $enabled = true;
    
    /**
     * The output channel to write logs to
     * @var IDebugOutput
     * @since 1.0-sofia
     */
    private $output;
    
    /**
     * Create a new pDebugger object
     * @param IDebugOutput $output The output to write the debugging logs to
     * @since 1.0-sofia
     */
    public function __construct($output){
        $this->output = $output;
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
                $dbt = reset(debug_backtrace());
                $where = sprintf('%s:%d', pPath::baseName($dbt['file']),
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
                    pPath::baseName($exception->getFile()),
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
            $dbt = reset(debug_backtrace());
            $message = sprintf(
                    'Time taken from application loaded to reach %s line %s',
                    $dbt['file'], $dbt['line']);
            $this->output->write($message,
                    $this->service('timer.app.start')->result() . 's',
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
            $where = sprintf('%s:%d', pPath::baseName($dbt['file']),
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
        if($this->enabled){
            $this->output->output();
        }
    }
    
}