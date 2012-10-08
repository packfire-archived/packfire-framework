<?php
pload('packfire.exception.pMissingDependencyException');
pload('pHttpResponse');

/**
 * pHttpServer class
 * 
 * Provides a HTTP server instance functionality
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpServer {
    
    /**
     * The hostname or IP address of the server
     * @var string
     * @since 1.0-sofia
     */
    private $host;
    
    /**
     * The port number to connect
     * @var integer
     * @since 1.0-sofia
     */
    private $port;
    
    /**
     * Create a new pHttpServer instance
     * @param string $host The hostname or IP address of the server
     * @param integer $port (optional) The port number to connect to,
     *                  defaults to HTTP port 80.
     * @since 1.0-sofia
     */
    public function __construct($host, $port = 80){
        $this->host = $host;
        $this->port = $port;
    }
    
    /**
     * Perform a HTTP request to the server
     * @param pHttpRequest $request The request to send to the server
     * @param pHttpResponse $response The response object to receive the response
     * @return pHttpResponse Returns the response object
     * @since 1.0-sofia
     */
    public function request($request, $response = null){
        if(function_exists('curl_init')){
            // according to RFC 2616, the port number is required in the
            // Host header unless it is port 80.
            $request->headers()->add('Host',
                    $this->host . ($this->port == 80 ? '' : ':' . $this->port));

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, $request->url());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_PORT, $this->port);
            // get the headers back too!
            curl_setopt($ch, CURLOPT_HEADER, true);
            
            // the headers to send
            $headers = array();
            foreach($request->headers() as $key => $value){
                $headers[] = $key . ': ' . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // if it is post
            if($request->method() == pHttpMethod::POST){
                curl_setopt($ch, CURLOPT_POST, $request->post()->count());
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request->post()->toArray()));
            }

            //execute and fetch result
            $result = curl_exec($ch);

            // check if it was timed out
            if(curl_errno($ch) == 28){
                throw new pIOException('HTTP request timeout.');
            }
            
            //close connection
            curl_close($ch);
            
            if(!$response){
                $response = new pHttpResponse();
            }
            $response->parse($result);
            return $response;
        }else{
            throw new pMissingDependencyException('CURL extension is required by pHttpServer but is not enabled.');
        }
    }
    
}