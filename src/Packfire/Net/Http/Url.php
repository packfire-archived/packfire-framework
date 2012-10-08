<?php
pload('packfire.io.file.pPath');
pload('packfire.collection.pMap');

/**
 * A URL representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pUrl {

    /**
     * Scheme / Protocol of the URL e.g. http, https, ftp
     * @var string
     * @since 1.0-sofia
     */
    private $scheme;

    /**
     * Host of the URL e.g. www.php.net, ftp.example.com
     * @var string
     * @since 1.0-sofia
     */
    private $host;

    /**
     * Port number in the URL e.g. 8080, 2084
     * @var integer
     * @since 1.0-sofia
     */
    private $port;

    /**
     * Username to login via URL
     * @var string
     * @since 1.0-sofia
     */
    private $user;

    /**
     * Password of the authentication in URL
     * @var string
     * @since 1.0-sofia
     */
    private $pass;

    /**
     * Requesting path of the URL e.g. /example/path/to/file.html
     * @var string
     * @since 1.0-sofia
     */
    private $path;

    /**
     * GET parameters
     * @var pMap
     * @since 1.0-sofia
     */
    private $params = array();

    /**
     * Fragment that appears after the hex (#)
     * @var string
     * @since 1.0-sofia
     */
    private $fragment;

    /**
     * Create a new URL based on a URL string
     * @param string $url (optional) Create the URL object based on a URL string
     * @since 1.0-sofia
     */
    public function __construct($url = null){
        if(func_num_args() == 1){
            $parts = parse_url($url);
            foreach($parts as $k => $v){
                $this->$k = $v;
            }
            if(isset($this->query)){
                parse_str($this->query, $this->params);
            }
            unset($this->query);
            if(!$this->port){
                switch($this->scheme){
                    case 'https':
                        $this->port = 443;
                        break;
                    case 'http':
                        $this->port = 80;
                        break;
                }
            }
        }
        $this->params = new Map($this->params);
    }

    /**
     * Get or set the fragment of the URL (i.e. string after the #)
     * @param string $f (optional) If specified, the function will set the new value.
     * @return string Returns the fragment of the URL
     * @since 1.0-sofia
     */
    public function fragment($f = null){
        if(func_num_args() == 1){
            $this->fragment = $f;
        }
        return $this->fragment;
    }

    /**
     * Get or set the host of the URL (e.g. www.google.com)
     * @param string $h (optional) If specified, the function will set the new value.
     * @return string Returns the host of the URL
     * @since 1.0-sofia
     */
    public function host($h = null){
        if(func_num_args() == 1){
            $this->host = $h;
        }
        return $this->host;
    }

    /**
     * Get or set the password in the URL
     * @param string $p (optional) If specified, the function will set the new value.
     * @return string Returns the password in the URL
     * @since 1.0-sofia
     */
    public function pass($p = null){
        if(func_num_args() == 1){
            $this->pass = $p;
        }
        return $this->pass;
    }

    /**
     * Get or set the port number of the URL (e.g. 8080)
     * @param string $p (optional) If specified, the function will set the new value.
     * @return string Returns the port number of the URL
     * @since 1.0-sofia
     */
    public function port($p = null){
        if(func_num_args() == 1){
            $this->port = $p;
        }
        return $this->port;
    }

    /**
     * Get or set the path of the URL (e.g. /home/path/to)
     * @param string $p (optional) If specified, the function will set the new value.
     * @return string Returns the path of the URL
     * @since 1.0-sofia
     */
    public function path($p = null){
        if(func_num_args() == 1){
            $this->path = $p;
        }
        return $this->path;
    }

    /**
     * Get or set the scheme of the URL (e.g. http, https, ftp)
     * @param string $s (optional) If specified, the function will set the new value.
     * @return string Returns the scheme of the URL
     * @since 1.0-sofia
     */
    public function scheme($s = null){
        if(func_num_args() == 1){
            $this->scheme = $s;
        }
        return $this->scheme;
    }

    /**
     * Get or set the username of the URL
     * @param string $u (optional) If specified, the function will set the new value.
     * @return string Returns the username
     * @since 1.0-sofia
     */
    public function user($u = null){
        if(func_num_args() == 1){
            $this->user = $u;
        }
        return $this->user;
    }

    /**
     * Get or set the GET parameters of the URL
     * @param Map $p (optional) If specified, the function will set the new value.
     * @return Map Returns the GET parameters in the URL
     * @since 1.0-sofia
     */
    public function params($p = null){
        if(func_num_args() == 1){
            $this->params = $p;
        }
        if(is_array($this->params)){
            $this->params = new Map($this->params);
        }
        return $this->params;
    }

    /**
     * Returns the URL object as a URL string
     * @return string Returns the URL string
     * @since 1.0-sofia
     */
    public function __toString(){
        $url = $this->scheme() . '://';
        if($this->user())
        {
            $url .= $this->user();
            if($this->pass()){
                $url .= ':' . $this->pass();
            }
            $url .= '@';
        }
        $url .= $this->host();
        if($this->port() != null && !in_array($this->scheme, array('https', 'http'))){
            $url .= ':'.$this->port();
        }
        $url .= $this->path();
        $query = http_build_query($this->params()->toArray());
        if($query){
            $url .= '?' . $query;
        }
        if($this->fragment()){
            $url .= '#' . $this->fragment();
        }
        return $url;
    }

    /**
     * URL encode a value so that it can be used safely in URL
     * @param mixed $var The raw value to encode
     * @return string The encoded value
     * @since 1.0-sofia
     */
    public static function encode($var){
        return urlencode($var);
    }

    /**
     * URL decode a value that was previously URL encoded
     * @param string $var The encoded value to decode.
     * @return mixed Returns the decoded value.
     * @since 1.0-sofia
     */
    public static function decode($var){
        return urldecode($var);
    }

    /**
     * Combine a base URL with a relative URL
     * @param string|pUrl $baseUrl The base URL
     * @param string $relativeUrl The relative URL to navigate based on the Base URL
     * @return pUrl Returns the combined URL
     * @since 1.0-sofia
     */
    public static function combine($baseUrl, $relativeUrl){
        if($baseUrl == ''){
            $path = $relativeUrl;
        }else{
            if(!($baseUrl instanceof self)){
                $baseUrl = new self($baseUrl);
            }
            $path = pPath::combine($baseUrl->path(), $relativeUrl);
        }
        $path = str_replace(array('\\','\\\\','//'), '/', $path);
        if($baseUrl instanceof self && $baseUrl->scheme()){
            $baseUrl->path($path);
        }else{
            $baseUrl = $path;
        }
        return $baseUrl;
    }
    
}

