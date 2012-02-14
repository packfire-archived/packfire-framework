<?php
pload('IComparator');

/**
 * A comparator that compares between two objects based on their fields in common
 * defined by a field selector.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire/collection/sort
 * @since 1.0-sofia
 */
class pObjectSelectedFieldComparator implements IComparator{
    
    /**
     * The function to select the 
     * @var callback|Closure
     * @since 1.0-sofia
     */
    private $fieldSelector;
    
    /**
     * Create a new pObjectSelectedfieldComparator
     * @param callback|Closure $fieldSelector The field selecting function
     * @since 1.0-sofia
     */
    public function __construct($fieldSelector){
        $this->fieldSelector = $fieldSelector;
    }

    /**
     * Compare two objects based on the selected field
     * @param array|object $a The first object to compare
     * @param array|object $b The second object to compare
     * @return integer Returns -1 if $a < $b, 1 if $a > $b or 0 otherwise.
     * @since 1.0-sofia
     */
    function compare($a, $b) {
        if ($this->access($a) == $this->access($b)) {
            return 0;
        }
        return ($this->access($a) < $this->access($b)) ? -1 : 1;
    }
    
    /**
     * Fetch the field value of an object
     * @param array|object $object
     * @return mixed Returns the field value
     * @since 1.0-sofia
     */
    private function access($object){
        $func = $this->fieldSelector;
        if($func instanceof Closure){
            return $func($object);
        }else{
            return call_user_func($func, $object);
        }
    }

}