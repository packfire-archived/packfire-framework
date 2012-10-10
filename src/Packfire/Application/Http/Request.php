<?php
namespace Packfire\Application\Http;

use Packfire\Application\IAppRequest;
use Packfire\Net\Http\ClientRequest;
use Packfire\Net\Http\Method as HttpMethod;

/**
 * Request class
 * 
 * An application HTTP Request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Http
 * @since 1.0-elenor
 */
class Request extends ClientRequest implements IAppRequest {
    
    /**
     * The script name called
     * @var string
     * @since 1.0-elenor
     */
    protected $scriptName;
    
    /**
     * The script name
     * @var string
     * @since 1.0-elenor
     */
    protected $phpSelf;
    
    /**
     * The path info provided
     * @var string
     * @since 1.0-elenor
     */
    private $pathInfo;
    
    /**
     * Create a new Request object
     * @param Client $client The client making the request
     * @param array $server The $_SERVER variables to pass in
     * @since 1.0-elenor
     */
    public function __construct($client, $server){
        parent::__construct($client);
        if($server){
            $this->scriptName = $server['SCRIPT_NAME'];
            $this->phpSelf = $server['PHP_SELF'];
            if(array_key_exists('ORIG_PATH_INFO', $server)){
                $this->pathInfo = $server['ORIG_PATH_INFO'];
            }elseif(array_key_exists('PATH_INFO', $server)){
                $this->pathInfo = $server['PATH_INFO'];
            }else{
                if($this->scriptName == $this->phpSelf){
                    $this->pathInfo = '/';
                }else{
                    $this->pathInfo = substr($this->phpSelf, strlen($this->scriptName));
                }
            }
        }
    }
    
    /**
     * Get the parameters of the request based on the HTTP method
     * @return Map Returns the parameters 
     * @since 1.0-sofia
     */
    public function params(){
        $result = new Map();
        $result->append($this->get());
        if($this->method() == HttpMethod::POST){
            $result->append($this->post());
        }
        return $result;
    }
    
    /**
     * Get the name of the script called
     * @return string Returns the name of the script
     * @since 1.0-elenor
     */
    public function scriptName(){
        return $this->scriptName;
    }
    
    /**
     * Get the PHP self value
     * @return string Returns the PHP self value
     * @since 1.0-elenor
     */
    public function phpSelf(){
        return $this->phpSelf;
    }
    
    /**
     * Get the path info
     * @return string Returns the path info
     * @since 1.0-elenor
     */
    public function pathInfo(){
        return $this->pathInfo;
    }
    
}