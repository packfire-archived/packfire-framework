<?php
pload('packfire.ioc.pBucketLoader');
pload('packfire.session.pSessionLoader');
pload('packfire.config.framework.pHttpRouterConfig');
pload('packfire.routing.http.pHttpRouter');
pload('packfire.exception.handler.pHttpExceptionHandler');

/**
 * pHttpServiceBucket class
 * 
 * Application service bucket that loads the application's HTTP services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.http
 * @since 1.0-sofia
 */
class pHttpServiceBucket extends pBucketLoader {
    
    /**
     * Perform loading
     * @since 1.0-elenor
     */
    public function load(){
        $this->put('exception.handler', new pHttpExceptionHandler());
        $this->put('config.routing', array('pHttpRouterConfig', 'load'));
        $this->put('router', new pHttpRouter());
        if($this->pick('config.app')){
            // load the debugger
            $this->put('debugger', new pDebugger());
            $this->pick('debugger')->enabled($this->pick('config.app')->get('app', 'debug'));
            if($this->pick('config.app')->get('session', 'enabled')){
                // load the session
                $sessionLoader = new pSessionLoader($this);
                $sessionLoader->load();
            }
        }
    }

}