<?php
namespace Packfire\Session\Storage;

use Packfire\Session\Bucket\ISessionBucket;

/**
 * ISessionStorage interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.storage
 * @since 1.0-sofia
 */
interface ISessionStorage extends ISessionBucket {
    
    /**
     * Regenerate the session
     * @param boolean $delete Set if the previous session should be deleted
     * @since 1.0-sofia 
     */
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