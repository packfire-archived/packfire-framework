<?php
pload('packfire.text.pNewline');
pload('packfire.text.pTextStream');
pload('pUrl');

/**
 * A HTTP Request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpRequest {
    
    /**
     * The method of request, e.g. GET, POST, HEAD
     * @var string
     * @since 1.0-sofia
     */
    private $method;

    /**
     * The requested path / URI e.g. /example/path/to/file.php
     * @var string
     * @since 1.0-sofia
     */
    private $uri;

    /**
     * The HTTP version called upon e.g. HTTP/1.0 or HTTP/1.1
     * @var string
     * @since 1.0-sofia
     */
    private $version;

    /**
     * Body of the Request
     * @var IInputStream
     * @since 1.0-sofia
     */
    private $body;

    /**
     * DateTime the request was made
     * @var pDateTime
     */
    private $time;

    /**
     * An array of the HTTP headers in the HTTP Response
     * @var pMap
     * @since 1.0-sofia
     */
    private $headers;

    /**
     * Whether the request is via HTTPS or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $https;

    /**
     * Cookies pertaining to this request
     * @var pMap
     * @since 1.0-sofia
     */
    private $cookies;

    /**
     * An array of POST data related to this request
     * @var pMap
     * @since 1.0-sofia
     */
    private $post;

    /**
     * An array of GET data related to this request
     * @var pMap
     * @since 1.0-sofia
     */
    private $get;
    
    /**
     * Create the pHttpRequest object
     */
    public function __construct(){
        $this->cookies = new pMap();
        $this->post = new pMap();
        $this->get = new pMap();
        $this->headers = new pMap();
    }
    
    /**
     * Parse the string format of the HTTP request into this object
     * @param string $s The string to be parsed
     * @return pHttpRequest Returns the request object
     * @since 1.0-sofia
     */
    public static function parse($strRequest){
        $strRequest = pNewline::neutralize($strRequest);
        $lines = explode(pNewline::UNIX, $strRequest);
        $request = new self();
        if(count($lines) > 0){
            $requestLine = $lines[0];
            list($method, $uri, $version) = explode(' ', $requestLine);
            $request->method(trim($method));
            $request->uri(trim($uri));
            $request->version(trim($version));
            unset($lines[0]);
            $body = null;
            foreach($lines as $line){
                if(strlen($line) > 0){
                    if($body === null){
                        $separator = strpos($line, ':');
                        if($separator){
                            $key = trim(substr($l, 0, $separator));
                            $value = trim(substr($l, $separator + 1));
                            if($request->headers()->keyExists($key)){
                                $request->headers()->get($key)->add(new pList(array($value)));
                            }else{
                                $request->headers()->add($key, new pList(array($value)));
                            }
                        }
                    }else{
                        $body .= $line . "\n";
                    }
                }else{
                    $body = '';
                }
            }
            if($body !== null){
                $request->body(new pTextStream($body));
            }
        }
        return $request;
    }

    /**
     * Get the first Request line
     * @return string Returns the line
     * @since 1.0-sofia
     */
    public function requestLine() {
        return $this->method() . ' ' . $this->uri() . ' ' . $this->version();
    }

    /**
     * Get or set the URI of the HTTP request
     * @param string $u (optional) If set, the new value will be set.
     * @return string Returns the URI of the request
     * @since 1.0-sofia
     */
    public function uri($uri = null){
        if(func_num_args() == 1){
            $this->uri = $uri;
        }
        return $this->uri;
    }

    /**
     * Get or set the method of the HTTP request
     * @param string $m (optional) Set the method
     * @return string Returns the method of the request
     * @since 1.0-sofia
     */
    public function method($m = null){
        if(func_num_args() == 1){
            $this->method = $m;
        }
        return $this->method;
    }

    /**
     * Get or set the version of the HTTP request
     * @param string $v (optional) If set, the new value will be set.
     * @return string Returns the HTTP request version
     * @since 1.0-sofia
     */
    public function version($v = null){
        if(func_num_args() == 1){
            $this->version = $v;
        }
        return $this->version;
    }

    /**
     * Get or set the input stream that reads body of the HTTP request
     * @param IInputStream $b (optional) If set, the new value will be set.
     * @return IInputStream Returns the input stream
     * @since 1.0-sofia
     */
    public function body($b = null){
        if(func_num_args() == 1){
            $this->body = $b;
        }
        return $this->body;
    }

    /**
     * Get or set the date/time of the HTTP request
     * @param pDateTime $t (optional) If set, the new value will be set.
     * @return pDateTime Returns the date time of the request
     * @since 1.0-sofia
     */
    public function time($t = null){
        if(func_num_args() == 1){
            $this->time = $t;
        }
        return $this->time;
    }

    /**
     * Get or set whether this request is HTTPS or not
     * @param boolean $h (optional) If set, the new value will be set.
     * @return boolean Returns whether the request is HTTPS or not
     * @since 1.0-sofia
     */
    public function https($h = null){
        if(func_num_args() == 1){
            $this->https = $h;
        }
        return $this->https;
    }
    
    /**
     * Get the hash map of GET parameters
     * @return pMap Returns the hash map
     * @since 1.0-sofia
     */
    public function get(){
        return $this->get;
    }
    
    /**
     * Get the hash map of POST parameters
     * @return pMap Returns the hash map
     * @since 1.0-sofia
     */
    public function post(){
        return $this->post;
    }
    
    /**
     * Get the hash map of cookies
     * @return pMap Returns the hash map
     * @since 1.0-sofia
     */
    public function cookies(){
        return $this->cookies;
    }
    
    /**
     * Get the hash map of headers
     * @return pMap Returns the hash map
     * @since 1.0-sofia
     */
    public function headers(){
        return $this->headers;
    }

    /**
     * Get this request's query string
     * @return string Returns the query string
     */
    public function queryString() {
        return http_build_query($this->get()->toArray(), '', '&');
    }

    /**
     * Returns the full URL of this request
     * @return pUrl Returns the URL
     */
    public function url(){
        $u = new pUrl();
        $u->host($this->headers->get('Host'));
        $u->scheme('http' . ($this->https() ? 's' : ''));
        $markpos = strpos($this->uri(), '?');
        if($markpos !== false){
            $u->path(substr($this->uri(), 0, $markpos));
        }else{
            $u->path($this->uri());
        }
        $u->params($this->get());
        return $u;
    }
    
}