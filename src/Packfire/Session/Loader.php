<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session;

use Packfire\Session\Storage\SessionStorage;
use Packfire\Session\Session;

/**
 * Performs loading for the session and its storage method
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Session
 * @since 1.0-sofia
 */
class Loader {
    
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