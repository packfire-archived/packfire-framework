<?php

/**
 * The root folder of the framework
 * @since 1.0-sofia
 */
define('__PACKFIRE_ROOT__', pathinfo(__FILE__, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR);

/**
 * The root folder of the application front controller
 * @since 1.0-sofia
 */
define('__APP_ROOT__', pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR);

define('__ENVIRONMENT__' , '');

require(__PACKFIRE_ROOT__ . 'helper.php');

pload('packfire.net.http.pHttpClient');
pload('packfire.net.http.pHttpClientRequest');
pload('packfire.io.file.pFileStream');
pload('packfire.datetime.pDateTime');

/**
 * The small fire you bring around in your pack to go around setting forests
 * on flames. Spark your web applications with Packfire today!
 *
 * @link http://www.github.com/thephpdeveloper/packfire
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class Packfire {
    
    /**
     * Start the framework execution
     * This is the entry point: this is it.
     * @param IApplication The application to start running
     * @since 1.0-sofia
     */
    public function fire($app){
        set_exception_handler(array($app, 'handleException'));
        $request = $this->loadRequest();
        try{
            $response = $app->receive($request);
            $this->processResponse($response);
        }catch(Exception $exception){
            throw $exception;
        }
    }
    
    /**
     * Prepare and load the client request
     * @return pHttpClientRequest The client's request
     * @since 1.0-sofia
     */
    private function loadRequest(){
        $client = new pHttpClient($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
        $request = new pHttpClientRequest($client);
        
        $request->method($_SERVER['REQUEST_METHOD']);
        $request->uri($_SERVER['REQUEST_URI']);
        $request->version($_SERVER['SERVER_PROTOCOL']);
        // changed to stream to prevent Denial Of Service
        $request->body(new pFileStream('php://input'));
        $request->time(pDateTime::fromTimestamp($_SERVER['REQUEST_TIME']));
        if(array_key_exists('HTTP_HOST', $_SERVER)){
            $request->headers()->add('Host', $_SERVER['HTTP_HOST'], true);
        }
        if(array_key_exists('HTTP_REFERER', $_SERVER)){
            $request->headers()->add('Referer', $_SERVER['HTTP_REFERER'], true);
        }
        if(array_key_exists('HTTP_CONNECTION', $_SERVER)){
            $request->headers()->add('Connection', $_SERVER['HTTP_CONNECTION'], true);
        }
        if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)){
            $request->headers()->add('Accept-Language', $_SERVER['HTTP_ACCEPT_LANGUAGE'], true);
        }
        if(array_key_exists('HTTP_ACCEPT_ENCODING', $_SERVER)){
            $request->headers()->add('Accept-Encoding', $_SERVER['HTTP_ACCEPT_ENCODING'], true);
        }
        if(array_key_exists('HTTP_ACCEPT_CHARSET', $_SERVER)){
            $request->headers()->add('Accept-Charset', $_SERVER['HTTP_ACCEPT_CHARSET'], true);
        }
        if(array_key_exists('HTTP_ACCEPT', $_SERVER)){
            $request->headers()->add('Accept', $_SERVER['HTTP_ACCEPT'], true);
        }
        if(array_key_exists('HTTP_USER_AGENT', $_SERVER)){
            $request->headers()->add('User-Agent', $_SERVER['HTTP_USER_AGENT'], true);
        }

        foreach ($_COOKIE as $k => $v) {
            $request->cookies()->add($k, $v);
        }

        foreach ($_POST as $k => $v) {
            $request->post()->add($k, $v);
        }

        foreach ($_GET as $k => $v) {
            $request->get()->add($k, $v);
        }

        $request->https((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'));
        return $request;
    }
    
    /**
     * Process the response and reply to the client
     * @param IAppResponse $response The response to reply
     * @since 1.0-sofia
     */
    public function processResponse($response){
        $response = $response->response();
        header($response->version() . ' ' . $response->code());
        foreach($response->headers() as $key => $value){
            header($key . ': ' . $value);
        }
        echo $response->body();
    }
    
}