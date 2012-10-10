<?php
namespace Packfire\Test\Mocks;

use Packfire\IoC\ServiceBucket as Bucket;

/**
 * ServiceBucket class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Test\Mocks
 * @since 1.0-sofia
 */
class ServiceBucket extends Bucket {
    
    public function mockService(){
        return $this;
    }
    
}