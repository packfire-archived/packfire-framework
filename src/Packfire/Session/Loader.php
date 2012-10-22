<?php
namespace Packfire\Session;

use Packfire\IoC\BucketLoader;
use Packfire\Session\Storage\SessionStorage;
use Packfire\Session\Session;

/**
 * Loader class
 * 
 * Performs loading for the session and its storage method
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session
 * @since 1.0-sofia
 */
class Loader extends BucketLoader {
    
    public function load(){
        $storageId = 'session.storage';
        $storage = $this->pick($storageId);
        if(!$storage){
            $storage = new SessionStorage();
            $this->put($storageId, $storage);
        }
        /* @var $config Config */
        $config = $this->pick('config.app');
        if($config){
            session_name($config->get('session', 'name'));
            session_set_cookie_params(
                    $config->get('session', 'lifetime'),
                    $config->get('session', 'path'),
                    $config->get('session', 'domain'),
                    $config->get('session', 'secure'),
                    $config->get('session', 'http')
                );
            session_start();
        }
        $this->put('session', new Session($storage));
    }
    
}