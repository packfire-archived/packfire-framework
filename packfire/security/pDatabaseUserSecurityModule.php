<?php
pload('ISecurityModule');

/**
 * A default database-based security module implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.security
 * @since 1.0-sofia
 */
class pDatabaseUserSecurityModule implements ISecurityModule {
    
    /**
     * The user identity
     * @var mixed
     * @since 1.0-sofia
     */
    private $user;
    
    public function authenticate($token) {
        $user = $this->identity();
        return ($token === $user['Password']);
    }

    public function authorize($route) {
        return true;
    }
    
    public function deauthenticate(){
        $this->identity(null);
    }

    public function identity($newIdentity = null) {
        $user = null;
        if(func_num_args() == 1){
            if($newIdentity){
                $user = $this->service('database')
                        ->from('users')
                        ->get(array('Username' => $newIdentity));
                $this->service('session')
                        ->set('security.user.id', $user['UserId']);
            }else{
                $this->session('session')->removeAt('security.user.id');
            }
        }else if($this->user){
            $user = $this->user;
        }else{
            $id = $this->service('session')->get('security.user.id');
            if($id !== null){
                $user = $this->service('database')
                        ->from('users')->get(array('UserId' => $id));
            }
        }
        if($user){
            $this->user = $user;
        }
        return $user;
    }
    
}