<?php
namespace Packfire\Net\Http;

use Packfire\Net\Http\Version;
use Packfire\Net\Http\ResponseCode;
use Packfire\Text\NewLine;
use Packfire\Collection\Map;
use Packfire\Exception\ParseException;

/**
 * Response class
 * 
 * A HTTP Response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Net\Http
 * @since 1.0-sofia
 */
class Response {

    /**
     * The HTTP Version of the Status-Line in the HTTP response
     * @var string
     * @since 1.0-sofia
     */
    protected $version = Version::HTTP_1_1;

    /**
     * The HTTP Status-Code of the Status-Line in the HTTP response
     * @var string
     * @since 1.0-sofia
     */
    protected $code = ResponseCode::HTTP_200;

    /**
     * Body of the HTTP Response
     * @var string
     * @since 1.0-sofia
     */
    protected $body = '';

    /**
     * An array of the HTTP headers in the HTTP Response
     * @var Map
     * @since 1.0-sofia
     */
    protected $headers;

    /**
     * Cookies to be sent to the client
     * @var Map
     * @since 1.0-sofia
     */
    protected $cookies;
    
    /**
     * Create a new Response object 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->headers = new Map();
        $this->cookies = new Map();
    }
    
    /**
     * Parse a HTTP response string into this object
     * @param string $strResponse The response to parse
     * @throws ParseException
     * @since 1.0-sofia
     */
    public function parse($strResponse){
        $strResponse = NewLine::neutralize($strResponse);
        $lines = explode(NewLine::UNIX, $strResponse);
        if(count($lines) > 0){
            $statusLine = $lines[0];
            $sp = strpos($statusLine, ' ');
            if($sp === false){
                throw new ParseException(
                        'Failed to parse HTTP version and code in response'
                    );
            }else{
                $this->version(trim(substr($statusLine, 0, $sp)));
                $this->code(trim(substr($statusLine, $sp + 1)));
            }
            unset($lines[0]);
            
            $body = null;

            foreach($lines as $line){
                if(strlen($line) > 0){
                    if($body === null){
                        $separator = strpos($line, ':');
                        if($separator !== false){
                            $key = trim(substr($line, 0, $separator));
                            $value = trim(substr($line, $separator + 1));
                            if($this->headers()->keyExists($key)){
                                $this->headers()->get($key)->add(new ArrayList(array($value)));
                            }else{
                                $this->headers()->add($key, new ArrayList(array($value)));
                            }
                        }
                    }else{
                        $body .= $line . NewLine::UNIX;
                    }
                }else{
                    $body = '';
                }
            }
            if($body === null){
                $body = '';
            }
            $this->body($body);
        }
    }
    
    /**
     * Get or set the status code of the HTTP response
     * @param string $code (optional) If set, the new value will be set.
     * @return string Returns the HTTP status code
     * @since 1.0-sofia
     */
    public function code($code = null){
        if(func_num_args() == 1){
            $this->code = $code;
        }
        return $this->code;
    }

    /**
     * Get or set the version of the HTTP response
     * @param string $version (optional) If set, the new value will be set.
     * @return string Returns the HTTP version
     * @since 1.0-sofia
     */
    public function version($version = null){
        if(func_num_args() == 1){
            $this->version = $version;
        }
        return $this->version;
    }

    /**
     * Get or set the body of the HTTP response
     * @param string $body (optional) If set, the new value will be set.
     * @return string Returns the body response
     * @since 1.0-sofia
     */
    public function body($body = null){
        if(func_num_args() == 1){
            $this->body = $body;
        }
        return $this->body;
    }

    /**
     * Get the collection that contains all the headers of the HTTP response
     * @return Map Returns the HTTP headers' hash map
     * @since 1.0-sofia
     */
    public function headers(){
        return $this->headers;
    }

    /**
     * Get the collection that contains all the cookies of the HTTP response
     * @return Map Returns the HTTP cookies hash
     * @since 1.0-sofia
     */
    public function cookies(){
        return $this->cookies;
    }

    public function __toString(){
        $buffer = '';
        $buffer = $this->version() . ' ' . $this->code() . NewLine::UNIX;
        foreach ($this->headers() as $k => $h) {
            if (is_array($h)) {
                foreach ($h as $d) {
                    $buffer .= $k . ': ' . $d .  NewLine::UNIX;
                }
            } else {
                    $buffer .= $k . ': ' . $h .  NewLine::UNIX;
            }
        }
        $buffer .=  NewLine::UNIX . $this->body();
        return $buffer;
    }
    
}