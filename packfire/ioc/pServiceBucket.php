<?php
pload('packfire.collection.pMap');

/**
 * pBucket Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.0-sofia
 */
class pServiceBucket {
    
    private $services;
    
    public function __construct() {
        $this->services = new pMap();
    }
    
    public function pick($serviceName){
        
    }
    
}