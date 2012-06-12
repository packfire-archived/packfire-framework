<?php
pload('packfire.ioc.pBucketUser');
pload('packfire.session.storage.pSessionStorage');
pload('packfire.session.pSession');
pload('packfire.session.pMessenger');

/**
 * pSessionLoader Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since version-created
 */
class pSessionLoader extends pBucketUser {
    
    public function load(){
        $storageId = 'session.storage';
        $storage = null;
        if($storageId){
            $storage = $this->service($storageId);
        }
        if(!$storage){
            $storage = new pSessionStorage();
            $this->services->put($storageId, $storage);
        }
        $this->services->put('session', new pSession($storage));
        $this->services->put('messenger', 'pMessenger');
    }
    
}