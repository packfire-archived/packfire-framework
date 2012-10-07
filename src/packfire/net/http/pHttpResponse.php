<?php
pload('packfire.application.IAppResponse');
pload('pHttpVersion');
pload('pHttpResponseCode');
pload('packfire.text.pNewline');
pload('packfire.collection.pMap');
pload('packfire.exception.pParseException');

/**
 * A HTTP Response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpResponse {

    /**
     * The HTTP Version of the Status-Line in the HTTP response
     * @var string
     * @since 1.0-sofia
     */
    protected $version = pHttpVersion::HTTP_1_1;

    /**
     * The HTTP Status-Code of the Status-Line in the HTTP response
     * @var string
     * @since 1.0-sofia
     */
    protected $code = pHttpResponseCode::HTTP_200;

    /**
     * Body of the HTTP Response
     * @var string
     * @since 1.0-sofia
     */
    protected $body = '';

    /**
     * An array of the HTTP headers in the HTTP Response
     * @var pMap
     * @since 1.0-sofia
     */
    protected $headers;

    /**
     * Cookies to be sent to the client
     * @var pMap
     * @since 1.0-sofia
     */
    protected $cookies;
    
    /**
     * Create a new pHttpResponse object 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->headers = new pMap();
        $this->cookies = new pMap();
    }
    
    /**
     * Parse a HTTP response string into this object
     * @param string $strResponse The response to parse
     * @throws pParseException
     * @since 1.0-sofia
     */
    public function parse($strResponse){
        $strResponse = pNewline::neutralize($strResponse);
        $lines = explode(pNewline::UNIX, $strResponse);
        if(count($lines) > 0){
            $statusLine = $lines[0];
            $sp = strpos($statusLine, ' ');
            if($sp === false){
                throw new pParseException(
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
                                $this->headers()->get($key)->add(new pList(array($value)));
                            }else{
                                $this->headers()->add($key, new pList(array($value)));
                            }
                        }
                    }else{
                        $body .= $line . pNewline::UNIX;
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
     * @return pMap Returns the HTTP headers' hash map
     * @since 1.0-sofia
     */
    public function headers(){
        return $this->headers;
    }

    /**
     * Get the collection that contains all the cookies of the HTTP response
     * @return pMap Returns the HTTP cookies hash
     * @since 1.0-sofia
     */
    public function cookies(){
        return $this->cookies;
    }

    public function __toString(){
        $buffer = '';
        $buffer = $this->version() . ' ' . $this->code() . pNewline::UNIX;
        foreach ($this->headers() as $k => $h) {
            if (is_array($h)) {
                foreach ($h as $d) {
                    $buffer .= $k . ': ' . $d .  pNewline::UNIX;
                }
            } else {
                    $buffer .= $k . ': ' . $h .  pNewline::UNIX;
            }
        }
        $buffer .=  pNewline::UNIX . $this->body();
        return $buffer;
    }
    
}