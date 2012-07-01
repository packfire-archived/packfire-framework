<?php
pload('packfire.ioc.pBucketUser');

/**
 * tMockBucketUser class
 * 
 * bucket user mock object
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0-sofia
 */
class tMockBucketUser extends pBucketUser {
    
    public function services(){
        return $this->services;
    }
    
}