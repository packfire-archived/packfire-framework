<?php
pload('packfire.ioc.pBucketUser');

/**
 * Session Flash service provider
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session
 * @since 1.0-sofia
 */
class pSessionFlash extends pBucketUser {
    
    /**
     * Get the flash message
     * @return string Returns the message from the session storage
     * @since 1.0-sofia 
     */
    public function get(){
        $session = $this->service('session');
        $message = $session->get('session.flash');
        $this->clear();
        return $message;
    }
    
    /**
     * Set the flash message
     * @param string $message The flash message
     * @since 1.0-sofia
     */
    public function set($message){
        $session = $this->service('session');
        $session->set('session.flash', $message);
    }
    
    /**
     * Clear the flash message from the session storage
     * @since 1.0-sofia 
     */
    public function clear(){
        $session = $this->service('session');
        $session->remove('session.flash');
    }
    
}