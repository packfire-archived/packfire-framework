<?php
pload('pDateTime');
pload('packfire.exception.pInvalidRequestException');

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
     * @throws pInvalidRequestException
     * @since 1.0-sofia
     */
    public function start(){
        if($this->running()){
            throw new pInvalidRequestException(
                    'pTimer::start() cannot be called when the timer is already running.'
                );
            return;
        }
        $this->startTime = pDateTime::microtime();
    }

    /**
     * Stop the timer and return the result
     * @return double|integer Returns the result of the timing
     * @throws pInvalidRequestException
     * @since 1.0-sofia
     */
    public function stop(){
        if(!$this->running()){
            throw new pInvalidRequestException(
                    'pTimer::stop() cannot be called when the timer is already stopped.'
                );
            return;
        }
        $this->endTime = pDateTime::microtime();
        return $this->result();
    }

    /**
     * Get the timing result from the start till stop. If the timer is still
     * running, the result from the start till the result call will be returned.
     * @return double|integer Returns the timing result in terms of seconds.
     * @throws pInvalidRequestException Throws when the timer has not started before yet
     * @since 1.0-sofia
     */
    public function result(){
        if(!$this->running() && !$this->endTime){
            throw new pInvalidRequestException(
                    'pTimer::result() cannot be called when the timer has not started yet.'
                );
        }
        $endTime = $this->endTime;
        if(!$endTime){
            $endTime = pDateTime::microtime();
        }
        return $endTime - $this->startTime;
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