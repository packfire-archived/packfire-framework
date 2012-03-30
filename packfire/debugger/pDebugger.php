<?php
pload('pConsoleDebugOutput');

/**
 * The debugger to help you debug in your application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger
 * @since 1.0-sofia
 */
class pDebugger extends pBucketUser {
    
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
     * Write a variable dump to the debugger log
     * @param mixed $value The variable to dump
     * @since 1.0-sofia 
     */
    public function dump($value){
        $output = var_export($value, true);
        $dbt = reset(debug_backtrace());
        $where = sprintf('%s:%d', pPath::baseName($dbt['file']), $dbt['line']);
        $this->output->write($output, $where, __FUNCTION__);
    }
    
    /**
     * Write a log message
     * @param string $message The log message to be written
     * @since 1.0-sofia 
     */
    public function log($message){
        $dbt = reset(debug_backtrace());
        $where = sprintf('%s:%d', pPath::baseName($dbt['file']), $dbt['line']);
        $this->output->write($message, $where, __FUNCTION__);
    }
    
    /**
     * Log an exception's details
     * @param Exception $exception The exception to be logged
     * @since 1.0-sofia
     */
    public function exception($exception){
        $where = sprintf('%s:%d', pPath::baseName($exception->getFile()), $exception->getLine());
        $message = sprintf('Error %s: %s', $exception->getCode(), $exception->getMessage());
        $this->output->write($message, $where, __FUNCTION__);
    }
    
    /**
     * Do a time check log.
     * Time taken from the application load to reach the time check will be shown on the log
     * @since 1.0-sofia 
     */
    public function timeCheck(){
        $dbt = reset(debug_backtrace());
        $message = sprintf('Time taken from application loaded to reach %s line %s', $dbt['file'], $dbt['line']);
        $this->output->write($message, $this->service('timer.app.start')->result() . 's', __FUNCTION__);
    }
    
    /**
     * Perform output when the debugger is destroyed, supposedly application end execution.
     * @internal
     * @ignore 
     */
    public function __destruct(){
        $this->output->output();
    }
    
}