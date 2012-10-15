<?php
namespace Packfire\DateTime;

use Packfire\Text\Inflector;
use Packfire\Text\Text;

class Describer {
    
    public function __construct(){
        
    }
    
    /**
     * 
     * @param DateTime $dateTime1
     * @param DateTime $dateTime2 (optional)
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
            /* @var $timeSpan TimeSpan */
            $verbs = array('day' => 'day', 'hour' => 'hr', 'minute' => 'min', 'second' => 'sec');
            foreach($verbs as $full => $short){
                $value = $timeSpan->$full();
                if($value > 0){
                    $desc[] = $value . ' ' . Inflector::quantify($value, $short);
                }
            }
            return Text::listing($desc) . ' ago';
        }
    }
    
}