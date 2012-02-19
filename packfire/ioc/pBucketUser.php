<?php
pload('IBucketUser');

/**
 * A more concrete implementation of a bucket user
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.0-sofia
 */
abstract class pBucketUser implements IBucketUser {
    
    /**
     * The bucket of services
     * @var pServiceBucket
     */
    protected $bucket;
    
    /**
     * 
     * @param type $bucket
     * @return type 
     */
    public function bucket($bucket = null){
        if(func_num_args() == 1){
            $this->bucket = $bucket;
        }
        return $this->bucket;
    }
    
}