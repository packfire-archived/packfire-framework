<?php
pload('pHttpClientRequest');

/**
 * pHttpPhpRequest class
 * 
 * A HTTP Request encapsulated by PHP
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-elenor
 */
class pHttpPhpRequest extends pHttpClientRequest {
    
    /**
     * The script name called
     * @var string
     * @since 1.0-elenor
     */
    private $scriptName;
    
    /**
     * The script name
     * @var string
     * @since 1.0-elenor
     */
    private $phpSelf;
    
    /**
     * Create a new pPhpHttpRequest
     * @param pHttpClient $client The client making the request
     * @param array $server The $_SERVER variables to pass in
     * @since 1.0-elenor
     */
    public function __construct($client, $server){
        parent::__construct($client);
        $this->scriptName = $server['SCRIPT_NAME'];
        $this->phpSelf = $server['PHP_SELF'];
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
    
}