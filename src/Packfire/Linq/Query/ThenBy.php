<?php
namespace Packfire\Linq\Query;

use Packfire\Linq\Query\OrderBy;

/**
 * ThenBy class
 *
 * LINQ Order Then By Query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class ThenBy extends OrderBy {

    /**
     * The previous query's callback
     * @var Closure|callback
     * @since 1.0-sofia
     */
    private $previousCallback;

    /**
     * Create a new ThenBy object
     * @param Closure|callback $worker The callback that will work on this query
     * @param Closure|callback $previousCallback The callback that is working for
     *              the previous query
     * @param boolean $descending Set whether the order is descending or not
     * @since 1.0-sofia
     */
    public function __construct($worker, $previousCallback, $descending = false){
        parent::__construct($worker, $descending);
        $this->previousCallback = $previousCallback;
    }

    /**
     * The comparison method working with the previous callback
     * @param mixed $a The first item to compare
     * @param mixed $b The second item to compare
     * @return integer Returns the comparison result -1, 0 or 1.
     * @internal
     * @since 1.0-sofia
     */
    public function compare($a, $b){
        $result = call_user_func($this->previousCallback, $a, $b);
        if($result != 0){
            return $result;
        }
        return parent::compare($a, $b);
    }

}