<?php
namespace Packfire\Application\Http;

use Packfire\Ioc\BucketLoader;
use Packfire\Session\Loader as SessionLoader;
use Packfire\Config\Framework\HttpRouterConfig;
use Packfire\Exception\Handler\HttpHandler as HttpExceptionHandler;
use Packfire\Debugger\Debugger;
use Packfire\Route\Http\Router as HttpRouter;

/**
 * ServiceBucket class
 * 
 * Application service bucket that loads the application's HTTP services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Http
 * @since 1.0-sofia
 */
class ServiceBucket extends BucketLoader {
    
    /**
     * Perform loading
     * @since 1.0-elenor
     */
    public function load(){
        if(!$this->contains('exception.handler')){
            $this->put('exception.handler', new HttpExceptionHandler());
        }
        $this->put('config.routing', array(new HttpRouterConfig(), 'load'));
        $this->put('router', new HttpRouter());
        if($this->pick('config.app')){
            // load the debugger
            $this->put('debugger', new Debugger());
            $this->pick('debugger')->enabled($this->pick('config.app')->get('app', 'debug'));
            if($this->pick('config.app')->get('session', 'enabled')){
                // load the session
                $sessionLoader = new SessionLoader($this);
                $sessionLoader->load();
            }
        }
    }

}