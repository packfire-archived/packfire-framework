<?php

/**
 * A user of the pServiceBucket
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.0-sofia
 */
interface IBucketUser {
    
    public function bucket($bucket = null);
    
}