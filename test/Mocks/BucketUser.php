<?php
namespace Packfire\Test\Mocks;

use Packfire\IoC\BucketUser as User;

/**
 * BucketUser class
 * 
 * bucket user mock object
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Test\Mocks
 * @since 1.0-sofia
 */
class BucketUser extends User {
    
    public function services(){
        return $this->services;
    }
    
}