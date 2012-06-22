<?php
pload('packfire.ioc.pBucketUser');
pload('ISecurityModule');

/**
 * The default security module
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.security
 * @since 1.0-sofia
 */
class pSecurityModule extends pBucketUser implements ISecurityModule {
    
    /**
     * The security module context
     * @var mixed
     * @since 1.0-sofia
     */
    private $context;
    
    /**
     * Authenticate the user
     * @return boolean Returns true if the user is authenticated, false otherwise.
     * @since 1.0-sofia
     */
    public function authenticate() {
        return true;
    }

    /**
     * Authorize the user to access a route
     * @param pRoute $route The route to check authorization
     * @return boolean Returns true if the user is authorized to access the route,
     *                 false otherwise.
     * @since 1.0-sofia
     */
    public function authorize($route) {
        return true;
    }

    /**
     * Retrieve the identity of the user
     * @param mixed $newIdentity (optional) The new identity in the session.
     * @return mixed Returns the identity of the user
     * @since 1.0-sofia
     */
    public function identity($newIdentity = null) {
        if(func_num_args() == 1){
            if($this->service('session')){
                $this->service('session')->bucket(__CLASS__)
                       ->set('identity', $newIdentity);
            }
        }
        if($this->service('session')){
            return $this->service('session')->bucket(__CLASS__)
                    ->get('identity');
        }
        return $newIdentity;
    }
    
    /**
     * Deauthenticate the user
     * @since 1.0-sofia 
     */
    public function deauthenticate(){
        $this->identity(null);
    }
    
    /**
     * Get or set the security context
     * @param mixed $context (optional) Set the security context
     * @return mixed Returns the security context
     * @since 1.0-sofia 
     */
    public function context($context = null){
        if(func_num_args() == 1){
            $this->context = $context;
        }
        return $this->context;
    }
    
}