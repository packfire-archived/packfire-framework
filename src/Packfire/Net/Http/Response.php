<?php
namespace Packfire\Net\Http;

use Packfire\Net\Http\Version;
use Packfire\Net\Http\ResponseCode;
use Packfire\Text\NewLine;
use Packfire\Collection\Map;
use Packfire\Exception\ParseException;
use Packfire\Collection\ArrayList;

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
       
        $matches = array();
        $okay = preg_match('`^([^\s]*) (.*)\n`', $strResponse, $matches);
        if(!$okay){
            throw new ParseException(
                    'Failed to parse HTTP version and code in response'
                );
        }
        $this->version = $matches[1];
        $this->code  = $matches[2];
        
        $firstLinePos = strpos($strResponse, "\n");
        $headerEnd = strpos($strResponse, "\n\n");
        Utility::parseHeaders(substr($strResponse, $firstLinePos + 1, $headerEnd - $firstLinePos - 1),
                $this->headers);
        if($this->headers->keyExists('set-cookie')){
            $cookies = $this->headers->get('set-cookie');
            if(!($cookies instanceof ArrayList)){
                $cookies = (array)$cookies;
            }
            foreach($cookies as $cookie){
                preg_match_all('/([^;=\s]+)\s*={0,1}\s*([^;=\s]*)/', $cookie, $matches); 
                $result = array_combine($matches[1], $matches[2]);
                foreach($result as $key => $value){
                    if(!preg_match('`(path|domain|secure|httponly|version|expires)`i', $key)){
                        $this->cookies[$key] = $value;
                    }
                }
            }
        }
        $this->body(substr($strResponse, $headerEnd + 2));
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