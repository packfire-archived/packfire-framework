<?php
pload('packfire.IAppResponse');
pload('pHttpVersion');
pload('pHttpResponseCode');
pload('packfire.text.pNewline');
pload('packfire.collection.pMap');
pload('packfire.exception.pParseException');

/**
 * A HTTP Response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpResponse implements IAppResponse {

    /**
     * The HTTP Version of the Status-Line in the HTTP response
     * @var string
     * @since 1.0-sofia
     */
    private $version = pHttpVersion::HTTP_1_1;

    /**
     * The HTTP Status-Code of the Status-Line in the HTTP response
     * @var string
     * @since 1.0-sofia
     */
    private $code = pHttpResponseCode::HTTP_200;

    /**
     * Body of the HTTP Response
     * @var string
     * @since 1.0-sofia
     */
    private $body = '';

    /**
     * An array of the HTTP headers in the HTTP Response
     * @var pMap
     * @since 1.0-sofia
     */
    private $headers;

    /**
     * Cookies to be sent to the client
     * @var pMap
     * @since 1.0-sofia
     */
    private $cookies;
    
    /**
     * Create a new pHttpResponse object 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->headers = new pMap();
        $this->cookies = new pMap();
    }
    
    /**
     * Parse a HTTP response string into a pHttpResponse object
     * @param string $strResponse The response to parse
     * @return pHttpResponse Returns the pHttpResponse object
     * @throws pParseException
     * @since 1.0-sofia
     */
    public static function parse($strResponse){
        $strResponse = pNewline::neutralize($strResponse);
        $lines = explode(pNewline::UNIX, $strResponse);
        $response = new self();
        if(count($lines) > 0){
            $statusLine = $lines[0];
            $sp = strpos($statusLine, ' ');
            if($sp === false){
                throw new pParseException(
                        sprintf('Failed to parse HTTP response')
                    );
            }else{
                $response->version(trim(substr($statusLine, 0, $sp)));
                $response->code(trim(substr($statusLine, $sp + 1)));
            }
            unset($lines[0]);
            
            $body = null;

            foreach($lines as $line){
                if(strlen($line) > 0){
                    if($body === null){
                        $separator = strpos($line, ':');
                        if($c){
                            $key = trim(substr($line, 0, $separator));
                            $value = trim(substr($line, $separator + 1));
                            if($response->headers()->keyExists($key)){
                                $response->headers()->get($key)->add(new pList(array($value)));
                            }else{
                                $response->headers()->add($key, new pList(array($value)));
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
            $response->body($body);
        }
        return $response;
    }
    
    /**
     * Get or set the status code of the HTTP response
     * @param string $v (optional) If set, the new value will be set.
     * @return string Returns the HTTP status code
     * @since 1.0-sofia
     */
    public function code($c = null){
        if(func_num_args() == 1){
            $this->code = $c;
        }
        return $this->code;
    }

    /**
     * Get or set the version of the HTTP response
     * @param string $v (optional) If set, the new value will be set.
     * @return string Returns the HTTP version
     * @since 1.0-sofia
     */
    public function version($v = null){
        if(func_num_args() == 1){
            $this->version = $v;
        }
        return $this->version;
    }

    /**
     * Get or set the body of the HTTP response
     * @param string $b (optional) If set, the new value will be set.
     * @return string Returns the body response
     * @since 1.0-sofia
     */
    public function body($b = null){
        if(func_num_args() == 1){
            $this->body = $b;
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
    
    public function response() {
        return $this;
    }
    
}