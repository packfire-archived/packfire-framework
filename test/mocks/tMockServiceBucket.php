<?php
pload('packfire.ioc.pServiceBucket');

/**
 * tMockServiceBucket Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0-sofia
 */
class tMockServiceBucket extends pServiceBucket {
    
    public function mockService(){
        return $this;
    }
    
}