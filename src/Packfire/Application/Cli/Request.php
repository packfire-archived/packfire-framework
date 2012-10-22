<?php
namespace Packfire\Application\Cli;

use Packfire\Application\IAppRequest;
use Packfire\Collection\Map;

/**
 * Request class
 * 
 * A request made via the command line
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-sofia
 */
class Request implements IAppRequest {
    
    /**
     * The parameters of the command request
     * @var Map
     * @since 1.0-elenor
     */
    protected $params;
    
    /**
     * Create a new Request object
     * @param array $arguments (optional) The array of arguments for the request.
     *           If not set, the arguments will be loaded from $_SERVER['argv'].
     * @since 1.0-elenor
     */
    public function __construct($arguments = null){
        if(!$arguments){
            $arguments = $_SERVER['argv'];
        }
        $this->params = new Map($arguments);
    }
    
    /**
     * Get the CLI arguments from the command parser
     * @return Map Returns the parameters 
     * @since 1.0-sofia
     */
    public function params() {
        return $this->params;
    }
    
    /**
     * Get the method of the application request
     * @return string Returns the method identifier 'cli'
     * @since 1.1-sofia
     */
    public function method(){
        return 'cli';
    }
    
}