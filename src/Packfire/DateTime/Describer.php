<?php
namespace Packfire\DateTime;

use Packfire\Text\Inflector;
use Packfire\Text\Text;

class Describer {
    
    /**
     * The components of the 
     * @var array
     * @since 2.0.0
     */
    private $components = array('day', 'hour', 'minute', 'second');
    
    private $verbs = array('day' => 'day', 'hour' => 'hour',
        'minute' => 'min', 'second' => 'sec');
    
    /**
     * Flag whether textual listing is performed on the output description
     * @var boolean
     * @since 2.0.0
     */
    private $listing = true;
    
    /**
     * The number of components to describe
     * @var integer
     * @since 2.0.0
     */
    private $limit;
    
    public function __construct(){
        
    }
    
    /**
     * Get or set the number of components to describe
     * @param integer $limit (optional) If set, the number of component to
     *                  describe will be set to this value. If set to null,
     *                  all components will be returned.
     * @return integer Returns the number of components. If there is no limit,
     *                  null will be returned.
     * @since 2.0.0
     */
    public function limit($limit = null){
        if(func_num_args()){
            $this->limit = $limit;
        }
        return $this->limit;
    }
    
    /**
     * Get or set if Describer should perform text listing on description
     * @param boolean $listing (optional) If set to true, listing will be done
     *              on the description, false otherwise.
     * @return boolean Returns whether listing is flagged.
     * @since 2.0.0
     */
    public function listing($listing = null){
        if(func_num_args()){
            $this->listing = $listing;
        }
        return $this->listing;
    }
    
    /**
     * Describe the time period between two date/time
     * 
     * Produces results such as : 1 hour, 24 mins and 20 secs
     * 
     * @param DateTime $dateTime1 The first date time
     * @param DateTime $dateTime2 (optional) The second date time. If not set,
     *              the current date/time will be used.
     * @return string Returns the text description of the time period
     * @since 2.0.0
     */
    public function describe($dateTime1, $dateTime2 = null){
        if(!$dateTime2){
            $dateTime2 = DateTime::now();
        }
        $timeSpan = $dateTime2->subtract($dateTime1);
        if($timeSpan->totalSeconds() === 0){
            return 'now';
        }else{
            $desc = array();
            $count = 0;
            /* @var $timeSpan TimeSpan */
            foreach($this->components as $component){
                if(isset($this->verbs[$component])){
                    $value = $timeSpan->$component();
                    if($value > 0){
                        $desc[] = $value . ' ' 
                                . Inflector::quantify($value, $this->verbs[$component]);
                        ++$count;
                        if($this->limit && $count >= $this->limit){
                            break;
                        }
                    }
                }
            }
            return $this->listing ? Text::listing($desc) : implode(' ', $desc);
        }
    }
    
}