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
    
    private $output;
    
    public function output($output = null){
        if(func_num_args() == 1){
            $this->output = $output;
        }
        return $this->output;
    }
    
}
