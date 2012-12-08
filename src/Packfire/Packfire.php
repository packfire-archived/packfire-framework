<?php
namespace Packfire;

/**
 * The small fire you bring around in your pack to go around setting forests
 * on flames. Spark your web applications with Packfire today!
 */

use Packfire\Net\Http\Client as HttpClient;
use Packfire\Application\Cli\Request as CliRequest;
use Packfire\Application\Cli\Response as CliResponse;
use Packfire\Application\Http\Request as HttpRequest;
use Packfire\Application\Http\Response as HttpResponse;
use Packfire\DateTime\DateTime;
use Packfire\Exception\ErrorException;
use Packfire\Core\ClassLoader\ClassLoader;
use Packfire\Core\ClassLoader\PackfireClassFinder;

/**
 * Packfire class
 *
 * Provides functionality to boot the application
 *
 * @link http://www.github.com/packfire
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire
 * @since 1.0-sofia
 */
class Packfire {
    
    /**
     * The framework class loader
     * @var ClassLoader
     */
    private $classLoader;
    
    /**
     * Create a new Packfire object
     * @since 2.0.0
     */
    public function __construct(){
        require(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');
        require(__DIR__ . DIRECTORY_SEPARATOR . 'Core/ClassLoader/IClassLoader.php');
        require(__DIR__ . DIRECTORY_SEPARATOR . 'Core/ClassLoader/IClassFinder.php');
        require(__DIR__ . DIRECTORY_SEPARATOR . 'Core/ClassLoader/PackfireClassFinder.php');
        require(__DIR__ . DIRECTORY_SEPARATOR . 'Core/ClassLoader/ClassLoader.php');
        $finder = new PackfireClassFinder();
        $this->classLoader = new ClassLoader($finder);
        require(__DIR__ . DIRECTORY_SEPARATOR . 'helper.php');
    }
    
    /**
     * Load the framework class loader
     * @return ClassLoader Returns the class loader that has been loaded.
     * @since 2.0.0
     */
    public function classLoader(){
        return $this->classLoader;
    }

    /**
     * Start the framework execution
     * This is the entry point: this is it.
     * @param IApplication $app The application to start running
     * @since 1.0-sofia
     */
    public function fire($app){
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            $e = new ErrorException($errstr);
            $e->setCode($errno);
            $e->setLine($errline);
            $e->setFile($errfile);
            throw $e;
        });
        set_exception_handler(array($app, 'handleException'));
        $request = $this->loadRequest();
        $response = $app->receive($request);
        $this->processResponse($app, $response);
    }

    /**
     * Prepare and load the client request
     * @return IAppRequest The client's request
     * @since 1.0-sofia
     */
    private function loadRequest(){
        if(php_sapi_name() == "cli") {
            $request = new CliRequest();
        }else{
            $agent = null;
            if(array_key_exists('HTTP_USER_AGENT', $_SERVER)){
                $agent = $_SERVER['HTTP_USER_AGENT'];
            }
            $client = new HttpClient($_SERVER['REMOTE_ADDR'], $agent);
            $request = new HttpRequest($client, $_SERVER);

            $request->method($_SERVER['REQUEST_METHOD']);
            $request->uri($_SERVER['REQUEST_URI']);
            $request->version($_SERVER['SERVER_PROTOCOL']);
            // changed to stream to prevent Denial Of Service
            $request->body(file_get_contents('php://input'));
            $request->time(DateTime::fromTimestamp($_SERVER['REQUEST_TIME']));
            if(array_key_exists('HTTP_HOST', $_SERVER)){
                $request->headers()->add('host', $_SERVER['HTTP_HOST'], true);
            }
            if(array_key_exists('HTTP_REFERER', $_SERVER)){
                $request->headers()->add('referer', $_SERVER['HTTP_REFERER'], true);
            }
            if(array_key_exists('HTTP_CONNECTION', $_SERVER)){
                $request->headers()->add('connection', $_SERVER['HTTP_CONNECTION'], true);
            }
            if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)){
                $request->headers()->add('accept-language', $_SERVER['HTTP_ACCEPT_LANGUAGE'], true);
            }
            if(array_key_exists('HTTP_ACCEPT_ENCODING', $_SERVER)){
                $request->headers()->add('accept-encoding', $_SERVER['HTTP_ACCEPT_ENCODING'], true);
            }
            if(array_key_exists('HTTP_ACCEPT_CHARSET', $_SERVER)){
                $request->headers()->add('accept-charset', $_SERVER['HTTP_ACCEPT_CHARSET'], true);
            }
            if(array_key_exists('HTTP_ACCEPT', $_SERVER)){
                $request->headers()->add('accept', $_SERVER['HTTP_ACCEPT'], true);
            }
            if(array_key_exists('HTTP_USER_AGENT', $_SERVER)){
                $request->headers()->add('user-agent', $_SERVER['HTTP_USER_AGENT'], true);
            }
            if(array_key_exists('HTTP_AUTHORIZATION', $_SERVER)){
                $request->headers()->add('authorization', $_SERVER['HTTP_AUTHORIZATION'], true);
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
        }
        return $request;
    }

    /**
     * Process the response and reply to the client
     * @param IApplication $app The application
     * @param IAppResponse $response The response to reply
     * @since 1.0-sofia
     */
    public function processResponse($app, $response){
        if($response instanceof HttpResponse){
            header($response->version() . ' ' . $response->code());
            foreach($response->headers() as $key => $value){
                header($key . ': ' . $value);
            }
            foreach($response->cookies() as $cookie){
                $cookie->set();
            }
            echo $response->output();
        }elseif($response instanceof CliResponse){
            $exitCode = $response->output();
            $app->service('shutdown')->add('shutdown.exitCode', function()use($exitCode){
                exit($exitCode);
            });
            
        }
    }

}