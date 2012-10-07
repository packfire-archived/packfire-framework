<?php
namespace Packfire\Application\Cli;
pload('packfire.ioc.pBucketLoader');
pload('packfire.routing.cli.pCliRouter');
pload('packfire.config.framework.pCliRouterConfig');
pload('packfire.exception.handler.pCliExceptionHandler');

/**
 * ServiceBucket class
 * 
 * The CLI application service bucket loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-elenor
 */
class ServiceBucket extends pBucketLoader {
    
    /**
     * Perform loading of services for CLI
     * @since 1.0-elenor
     */
    public function load(){
        if(!$this->contains('exception.handler')){
            $this->put('exception.handler', new pCliExceptionHandler());
        }
        $this->put('config.routing', array(new pCliRouterConfig(), 'load'));
        $this->put('router', new pCliRouter());
        if($this->pick('config.app')){
            // load the debugger
            $this->put('debugger', new pDebugger());
            $this->pick('debugger')->enabled(false);
        }
    }
    
}