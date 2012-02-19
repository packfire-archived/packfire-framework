<?php
pload('packfire.session.bucket.ISessionBucket');

/**
 * Session Storage interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.storage
 * @since 1.0-sofia
 */
interface ISessionStorage extends ISessionBucket {
    
    public function regenerate($delete = false);
    
    /**
     * Register a bucket into the session storage
     * @param ISessionBucket $bucket The bucket to be registered.
     * @since 1.0-sofia 
     */
    public function register($bucket);
    
    /**
     * Get the bucket in the storage by the ID
     * @param string $id The identifier of the storage
     * @return ISessionBucket Returns the session bucket 
     * @since 1.0-sofia
     */
    public function bucket($id);
    
}