<?php
pload('pConsoleDebugOutput');

/**
 * pDebugger Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger
 * @since 1.0-sofia
 */
class pDebugger extends pBucketUser {
    
    /**
     *
     * @var IDebugOutput
     */
    private $output;
    
    /**
     *
     * @param IDebugOutput $output 
     */
    public function __construct($output){
        $this->output = $output;
    }
    
    public function dump($value){
        $output = var_export($value, true);
        $dbt = reset(debug_backtrace());
        $where = sprintf('%s:%d', pPath::baseName($dbt['file']), $dbt['line']);
        $this->output->write($output, $where, __METHOD__);
    }
    
    public function log($message){
        $dbt = reset(debug_backtrace());
        $where = sprintf('%s:%d', pPath::baseName($dbt['file']), $dbt['line']);
        $this->output->write($message, $where, __METHOD__);
    }
    
    /**
     *
     * @param pException $exception 
     */
    public function exception($exception){
        $where = sprintf('%s:%d', pPath::baseName($exception->getFile()), $exception->getLine());
        $message = sprintf('Error %s: %s', $exception->getCode(), $exception->getMessage());
        $this->output->write($message, $where, __METHOD__);
    }
    
    public function timeCheck(){
        $dbt = reset(debug_backtrace());
        $message = sprintf('Time taken from application loaded to reach %s line %s', $dbt['file'], $dbt['line']);
        $this->output->write($message, $this->service('timer.app.start')->result(), __METHOD__);
    }
    
    public function __destruct(){
        $this->output->output();
    }
    
}