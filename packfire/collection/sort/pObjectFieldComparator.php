<?php
Packfire::load('IComparator');

/**
 * A comparator that compares between two objects based on their field in common.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire/collection/sort
 * @since 1.0-sofia
 */
class pObjectFieldComparator implements IComparator{
    
    /**
     * The field to compare
     * @var string
     * @since 1.0-sofia
     */
    private $field;
    
    /**
     * Create a new pObjectFieldComparator 
     * @param string $field The field of the objects to compare
     * @since 1.0-sofia
     */
    public function __construct($field){
        $this->field = $field;
    }

    /**
     * Compare two objects based on the defined field
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
        if(is_array($object)){
            return $object[$this->field];
        }else{
            return $object->{$this->field};
        }
    }

}