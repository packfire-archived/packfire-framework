<?php
pload('pHttpClientOS');

/**
 * pHttpClient Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpClient {
    
    /**
     * The IP Address of the client
     * @var string 
     */
    private $ipAddress;
    
    /**
     * User agent of the client
     * @var string
     */
    private $userAgent;
    
    /**
     * The operating system of the client
     * @var string 
     */
    private $operatingSystem;
    
    /**
     * The name of the browser
     * @var string 
     */
    private $browserName;
    
    /**
     * The browser version
     * @var string
     */
    private $browserVersion;
    
    /**
     * Flags whether the client is a known bot or not.
     * @var boolean
     */
    private $bot = false;
    
    public function __construct($ip, $userAgent){
        $this->ipAddress = $ip;
        $this->userAgent = $userAgent;
        $this->detect();
    }
    
    /**
     * Auto detect parameters from the user agent
     */
    protected function detect(){
        if (strpos($this->userAgent, 'Windows')) {
            $this->operatingSystem = pHttpClientOS::WINDOWS;
        } else if (strpos($this->userAgent, 'iPhone')) {
            $this->operatingSystem = pHttpClientOS::IOS;
        } else if (strpos($this->userAgent,'BlackBerry')) {
            $this->operatingSystem = pHttpClientOS::BLACKBERRY;
        } else if (strpos($this->userAgent,'Macintosh')) {
            $this->operatingSystem = pHttpClientOS::MACINTOSH;
        } else if (strpos($this->userAgent,'Android')) {
            $this->operatingSystem = pHttpClientOS::ANDROID;
        } else if (strpos($this->userAgent, 'Linux')) {
            $this->operatingSystem = pHttpClientOS::LINUX;
        } else if (strpos($this->userAgent, 'Unix')) {
            $this->operatingSystem = pHttpClientOS::UNIX;
        } else {
            $this->operatingSystem = pHttpClientOS::UNKNOWN;;
        }
        
        $matched = array();
        if(preg_match( '`Opera/([0-9].[0-9]{1,2})`', $this->userAgent, $matched)){
            $browser_version = $matched[1];
            $browser = pHttpClientBrowser::OPERA;
        }elseif(preg_match('`MSIE ([0-9].[0-9]{1,2})`', $this->userAgent, $matched)){
            $browser_version = $matched[1];
            $browser = pHttpClientBrowser::IE;
        }elseif(preg_match('`Firefox/([0-9\.]+)`', $this->userAgent, $matched)){
                $browser_version = $matched[1];
                $browser = pHttpClientBrowser::FIREFOX;
        }elseif(preg_match('`Safari/([0-9\.]+)`', $this->userAgent, $matched)){
                $browser_version = $matched[1];
                $browser = pHttpClientBrowser::SAFARI;
        }elseif(preg_match('`Chrome/([0-9\.]+)`', $this->userAgent, $matched)){
                $browser_version = $matched[1];
                $browser = pHttpClientBrowser::CHROME;
        }else{
            // browser not recognized!
            $browser_version = '';
            $browser = pHttpClientBrowser::UNKNOWN;
        }
        $this->browserName = $browser;
        $this->browserVersion = $browser_version;
        
        // A list of known robots
        $botArray = self::knownBots();
        
        // replace all but alphabets
        $ua = strtolower(preg_replace('/[^a-zA-Z _]*/', '', $this->userAgent));
        // check for intersections
        $this->bot = (bool)count(array_intersect(explode(' ', $ua), $botArray));
    }
    
    /**
     * Get the list of known bots
     * @return array Returns the list
     * @since 1.0-sofia
     */
    public static function knownBots(){
        $botArray = array('jeevesteoma', 'msnbot', 'slurp', 'jeevestemoa',
            'gulper', 'googlebot', 'linkwalker', 'validator', 'webaltbot',
            'wget', 'bot');
        return $botArray;
    }
    
    /**
     * Get the browser name of this client
     * @return string
     * @since 1.0-sofia
     */
    public function browserName(){
        return $this->browserName;
    }


    /**
     * Get the browser version of this client
     * @return string
     * @since 1.0-sofia
     */
    public function browserVersion(){
        return $this->browserVersion;
    }


    /**
     * Get the operating system of this client
     * @return string
     * @since 1.0-sofia
     */
    public function operatingSystem(){
        return $this->operatingSystem;
    }

    /**
     * Get the User Agent string of this client
     * @return string
     * @since 1.0-sofia
     */
    public function userAgent(){
        return $this->userAgent;
    }

    /**
     * Get the remote IP Address of this client
     * @return string
     * @since 1.0-sofia
     */
    public function ipAddress(){
        return $this->ipAddress;
    }

    /**
     * Query whether this client is bot or not
     * @return boolean
     * @since 1.0-sofia
     */
    public function bot(){
        return $this->bot;
    }
    
}