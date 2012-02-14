<?php
pload('pDateTime');

/**
 * A timer in microseconds
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
class pTimer {

    /**
     * The start time of the timer
     * @var integer|double
     * @since 1.0-sofia
     */
    private $startTime = false;

    /**
     * The end time of the timer
     * @var integer|double
     * @since 1.0-sofia
     */
    private $endTime = false;

    /**
     * Creates a new pTimer
     * @param boolean $start Set whether to start or don't start the timer upon
     *                       creation (optional, default FALSE).
     * @since 1.0-sofia
     */
    function __construct($start = false){
        if($start){
            $this->start();
        }
    }

    /**
     * Start the timer
     * @since 1.0-sofia
     */
    public function start(){
        if($this->running()){
            //throw new RaiseInvalidRequestException('Timer is already running. start() cannot be called when the Timer is running.');
            // TODO: throw invalid request exception
            return;
        }
        $this->startTime = pDateTime::microtime();
    }

    /**
     * Stop the timer and return the result
     * @return double|integer Returns the result of the timing
     * @since 1.0-sofia
     */
    public function stop(){
        if(!$this->running()){
            //throw new RaiseInvalidRequestException('Timer cannot be stopped if it is not started in the first place.');
            // TODO: throw invalid request exception
            return;
        }
        $this->endTime = pDateTime::microtime();
        return $this->result();
    }

    /**
     * Get the timing result after the last stop
     * @return double|integer Returns the result of the previous run.
     * @since 1.0-sofia
     */
    public function result(){
        if($this->running() || !$this->endTime){
            // TODO: invalid request
        }
        return $this->endTime - $this->startTime;
    }

    /**
     * Reset the timer
     * @since 1.0-sofia
     */
    public function reset(){
        $this->startTime = false;
        $this->endTime = false;
    }

    /**
     * Check if the timer is running or not
     * @return boolean Returns true if the timer is running, false otherwise.
     * @since 1.0-sofia
     */
    public function running(){
        return $this->startTime && !$this->endTime;
    }

}