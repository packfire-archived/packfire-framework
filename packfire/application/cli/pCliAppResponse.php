<?php
pload('packfire.application.IAppResponse');

/**
 * IAppResponse interface
 * 
 * Abstraction for application response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.cli
 * @since 1.0-elenor
 */
class pCliAppResponse implements IAppResponse {
    
    /**
     * The exit code for the application response
     * @var integer
     * @since 1.0-elenor
     */
    private $output;
    
    /**
     * Get or set the exit code of the response
     * @param integer $output (optional) Set the exit code
     * @return integer Returns the exit code
     * @since 1.0-elenor
     */
    public function output($output = null){
        if(func_num_args() == 1){
            $this->output = $output;
        }
        return $this->output;
    }
    
}
