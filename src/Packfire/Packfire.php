<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

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
use Packfire\FuelBlade\Container;

/**
 * Provides functionality to boot the application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire
 * @since 1.0-sofia
 * @link http://www.github.com/packfire
 */
class Packfire
{
    private $ioc;

    /**
     * Create a new Packfire object
     * @since 2.0.0
     */
    public function __construct()
    {
        if (!defined('__APP_ROOT__')) {
            define('__APP_ROOT__', dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . 'pack');
        }
        if (!class_exists('Packfire\Core\ClassLoader\PackfireClassFinder')) {
            if (is_dir(__DIR__ . '/../../vendor')) {
                require(__DIR__ . '/../../vendor/autoload.php');
            }

            require(__DIR__ . '/Core/ClassLoader/IClassLoader.php');
            require(__DIR__ . '/Core/ClassLoader/IClassFinder.php');
            require(__DIR__ . '/Core/ClassLoader/PackfireClassFinder.php');
            require(__DIR__ . '/Core/ClassLoader/ClassLoader.php');
        }
        require(__DIR__ . '/helper.php');
        $this->ioc = new Container();
        $this->ioc['autoload.finder'] = new PackfireClassFinder();
        $this->ioc['autoload.loader'] = new ClassLoader();
        $this->ioc['autoload.loader']->register();
    }

    /**
     * Start the framework execution
     * This is the entry point: this is it.
     * @param \Packfire\Application\IApplication $app The application to start running
     * @since 1.0-sofia
     */
    public function fire($app)
    {
        $app($this->ioc);
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            $e = new ErrorException($errstr);
            $e->setCode($errno);
            $e->setLine($errline);
            $e->setFile($errfile);
            throw $e;
        });
        set_exception_handler(array($app, 'handleException'));
        $request = $this->loadRequest();
        $this->ioc['request'] = $this->ioc->share(function() use ($request) {
            return $request;
        });
        $app->process();
        if (isset($this->ioc['response'])) {
            $response = $this->ioc['response'];
            $this->processResponse($app);
        }
    }

    /**
     * Prepare and load the client request
     * @return IAppRequest The client's request
     * @since 1.0-sofia
     */
    private function loadRequest()
    {
        if (php_sapi_name() == "cli") {
            $request = new CliRequest();
        } else {
            $agent = null;
            if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
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
            if (array_key_exists('HTTP_HOST', $_SERVER)) {
                $request->headers()->add('host', $_SERVER['HTTP_HOST'], true);
            }
            if (array_key_exists('HTTP_REFERER', $_SERVER)) {
                $request->headers()->add('referer', $_SERVER['HTTP_REFERER'], true);
            }
            if (array_key_exists('HTTP_CONNECTION', $_SERVER)) {
                $request->headers()->add('connection', $_SERVER['HTTP_CONNECTION'], true);
            }
            if (array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
                $request->headers()->add('accept-language', $_SERVER['HTTP_ACCEPT_LANGUAGE'], true);
            }
            if (array_key_exists('HTTP_ACCEPT_ENCODING', $_SERVER)) {
                $request->headers()->add('accept-encoding', $_SERVER['HTTP_ACCEPT_ENCODING'], true);
            }
            if (array_key_exists('HTTP_ACCEPT_CHARSET', $_SERVER)) {
                $request->headers()->add('accept-charset', $_SERVER['HTTP_ACCEPT_CHARSET'], true);
            }
            if (array_key_exists('HTTP_ACCEPT', $_SERVER)) {
                $request->headers()->add('accept', $_SERVER['HTTP_ACCEPT'], true);
            }
            if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
                $request->headers()->add('user-agent', $_SERVER['HTTP_USER_AGENT'], true);
            }
            if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) {
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
     * @since 1.0-sofia
     */
    public function processResponse($app)
    {
        $response = $this->ioc['response'];
        if ($response instanceof HttpResponse) {
            header($response->version() . ' ' . $response->code());
            foreach ($response->headers() as $key => $value) {
                header($key . ': ' . $value);
            }
            foreach ($response->cookies() as $cookie) {
                $cookie->set();
            }
            echo $response->output();
        } elseif ($response instanceof CliResponse) {
            $exitCode = $response->output();
            if (isset($this->ioc['shutdown'])) {
                $this->ioc['shutdown']->add('shutdown.exitCode', function() use ($exitCode) {
                    exit($exitCode);
                });
            }
        }
    }

}
