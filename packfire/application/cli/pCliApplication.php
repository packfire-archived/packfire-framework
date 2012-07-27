<?php
pload('packfire.application.pServiceApplication');
pload('pCliServiceBucket');

/**
 * pCliApplication class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.cli
 * @since 1.0-elenor
 */
class pCliApplication extends pServiceApplication {
    
    public function __construct(){
        parent::__construct();
        
        $cliLoader = new pCliServiceBucket($this->services);
        $cliLoader->load();
    }
    
    public function handleException($exception) {
        
    }

    public function receive($request) {
        
    }
    
}