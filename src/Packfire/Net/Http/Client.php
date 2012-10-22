<?php
namespace Packfire\Net\Http;

use Packfire\Net\Http\ClientOS;
use Packfire\Net\Http\ClientBrowser;

/**
 * Client class
 * 
 * A HTTP client representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Net\Http
 * @since 1.0-sofia
 */
class Client {
    
    /**
     * The IP Address of the client
     * @var string 
     * @since 1.0-sofia
     */
    private $ipAddress;
    
    /**
     * User agent of the client
     * @var string
     * @since 1.0-sofia
     */
    private $userAgent;
    
    /**
     * The operating system of the client
     * @var string 
     * @since 1.0-sofia
     */
    private $operatingSystem;
    
    /**
     * The name of the browser
     * @var string 
     * @since 1.0-sofia
     */
    private $browserName;
    
    /**
     * The browser version
     * @var string
     * @since 1.0-sofia
     */
    private $browserVersion;
    
    /**
     * Flags whether the client is a known bot or not.
     * @var boolean
     * @since 1.0-sofia
     */
    private $bot = false;
    
    /**
     * Create a new Client object
     * @param string $ip The IP Address of the client
     * @param string $userAgent The user agent of the client
     * @since 1.0-sofia
     */
    public function __construct($ip, $userAgent){
        $this->ipAddress = $ip;
        $this->userAgent = $userAgent;
        $this->detect();
    }
    
    /**
     * Auto detect parameters from the user agent
     * @since 1.0-sofia
     */
    protected function detect(){
        if (strpos($this->userAgent, 'Windows')) {
            $this->operatingSystem = ClientOS::WINDOWS;
        } else if (strpos($this->userAgent, 'iPhone')) {
            $this->operatingSystem = ClientOS::IOS;
        } else if (strpos($this->userAgent,'BlackBerry')) {
            $this->operatingSystem = ClientOS::BLACKBERRY;
        } else if (strpos($this->userAgent,'Macintosh')) {
            $this->operatingSystem = ClientOS::MACINTOSH;
        } else if (strpos($this->userAgent,'Android')) {
            $this->operatingSystem = ClientOS::ANDROID;
        } else if (strpos($this->userAgent, 'Linux')) {
            $this->operatingSystem = ClientOS::LINUX;
        } else if (strpos($this->userAgent, 'Unix')) {
            $this->operatingSystem = ClientOS::UNIX;
        } else if (strpos($this->userAgent, 'Googlebot')) {
            $this->operatingSystem = ClientOS::GOOGLEBOT;
        } else if (strpos($this->userAgent, 'Yahoo!')) {
            $this->operatingSystem = ClientOS::YAHOOBOT;
        } else if (strpos($this->userAgent, 'bingbot')) {
            $this->operatingSystem = ClientOS::BINGBOT;
        } else if (strpos($this->userAgent, 'msnbot')) {
            $this->operatingSystem = ClientOS::MSNBOT;
        } else {
            $this->operatingSystem = ClientOS::UNKNOWN;;
        }
        
        $matched = array();
        if(preg_match( '`Opera/([0-9].[0-9]{1,2})`', $this->userAgent, $matched)){
            $browser_version = $matched[1];
            $browser = ClientBrowser::OPERA;
        }elseif(preg_match('`MSIE ([0-9].[0-9]{1,2})`', $this->userAgent, $matched)){
            $browser_version = $matched[1];
            $browser = ClientBrowser::IE;
        }elseif(preg_match('`Firefox/([0-9\.]+)`', $this->userAgent, $matched)){
                $browser_version = $matched[1];
                $browser = ClientBrowser::FIREFOX;
        }elseif(preg_match('`Chrome/([0-9\.]+)`', $this->userAgent, $matched)){
                $browser_version = $matched[1];
                $browser = ClientBrowser::CHROME;
        }elseif(preg_match('`Safari/([0-9\.]+)`', $this->userAgent, $matched)){
                $browser_version = $matched[1];
                $browser = ClientBrowser::SAFARI;
        }else{
            // browser not recognized!
            $browser_version = '';
            $browser = ClientBrowser::UNKNOWN;
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