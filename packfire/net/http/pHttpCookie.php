<?php
pload('packfire.datetime.pDateTime');
pload('packfire.exception.pInvalidArgumentException');

/**
 * A HTTP Cookie
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpCookie {
    
    /**
     * Name of the cookie
     * @var string
     * @since 1.0-sofia
     */
    private $name;

    /**
     * Value of the cookie
     * @var string
     * @since 1.0-sofia
     */
    private $value;

    /**
     * The expiry date / time of the cookie in DateTime or UNIX Epoch
     * @var pDateTime
     * @since 1.0-sofia
     */
    private $expire;

    /**
     * Path on the server which the cookie will be available on. Defaults to '/'
     * @var string
     * @since 1.0-sofia
     */
    private $path = '/';

    /**
     * Domain that the cookie will be available on
     * @var string
     * @since 1.0-sofia
     */
    private $domain;

    /**
     * Set cookie to secure connection only via HTTPS
     * @var boolean
     */
    private $secure = false;

    /**
     * Set this to TRUE if you only want the cookie to be accessible via HTTP
     * This means that scripting languages such as Javascript will not be able
     * to access it if it is set to TRUE.
     * @var boolean
     * @since 1.0-sofia
     */
    private $httpOnly = false;

    /**
     * Creates a new Cookie object
     * @param string $n Name of the Cookie
     * @param mixed $k (optional) Value of the Cookie variable
     * @since 1.0-sofia
     */
    function __construct($n, $k = null) {
        $this->name($n);
        if ($k !== null) {
            $this->value($k);
        }
        $this->expire(pDateTime::fromTimestamp(time() + 36000));
    }

    /**
     * Get or set the name of the cookie
     * @param string $n (optional) If $n is passed in as argument, it will set the name of the cookie
     * @return string
     */
    public function name($n = false){
        if(func_num_args() == 1){
            $this->name = $n;
        }
        return $this->name;
    }

    /**
     * Get or set the value of the cookie
     * @param mixed $v (optional) If $v is passed in as argument, it will set the value of the cookie
     * @return mixed
     */
    public function value($v = false){
        if(func_num_args() == 1){
            $this->value = $v;
        }
        return $this->value;
    }

    /**
     * Get or set the path of the cookie in which the cookie resides
     * @param string $p (optional) If $p is passed in as argument, it will set the path of the cookie in which the cookie resides
     * @return string
     */
    public function path($p = false){
        if(func_num_args() == 1){
            $this->path = $p;
        }
        return $this->path;
    }

    /**
     * Get or set whether the cookie can only be used via HTTPS
     * @param boolean $s (optional) If $s is passed in as argument, it will set the cookie can only be used via HTTPS
     * @return boolean
     */
    public function secure($s = false){
        if(func_num_args() == 1){
            $this->secure = $s;
        }
        return $this->secure;
    }

    /**
     * Get or set the expiry date / time of the cookie
     * @param pDateTime $d (optional) Set the value of expiry date/time.
     * @return pDateTime Returns the expiry date time of the cookie
     * @since 1.0-sofia
     */
    public function expire($d = false){
        if(func_num_args() == 1){
            if(!($d instanceof pDateTime)){
                throw new pInvalidArgumentException('pHttpCookie::expire() expects the first argument to be of type pDateTime.');
            }
            $this->expire = $d;
        }
        return $this->expire;
    }

    /**
     * Get or set the domain in which the cookie resides
     * @param string $d (optional) If $d is passed in as argument, it will set the value of domain.
     * @return string
     * @since 1.0-sofia
     */
    public function domain($d = false){
        if(func_num_args() == 1){
            $this->domain = $d;
        }
        return $this->domain;
    }

    /**
     * Get or set whether the cookie is for HTTP only
     * @param boolean $h (optional) If $h is passed in as argument, it will set the value of httpOnly.
     * @return boolean
     * @since 1.0-sofia
     */
    public function httpOnly($h = false){
        if(func_num_args() == 1){
            $this->httpOnly = $h;
        }
        return $this->httpOnly;
    }

    /**
     * Set and commit this cookie to webpage header response
     * @return boolean
     * @since 1.0-sofia
     */
    public function set() {
        return setcookie($this->name, $this->value,
                $this->expire->toTimestamp(),
                $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    /**
     * Remove this cookie from the webpage header response
     * @since 1.0-sofia
     */
    public function remove(){
        $this->expire = -36000;
        $this->value = null;
        $this->set();
    }
    
}