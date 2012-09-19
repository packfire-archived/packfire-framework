<?php
pload('IComparator');

/**
 * A comparator that compares between two objects based on their field in common.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection.sort
 * @since 1.0-sofia
 */
class pObjectFieldComparator implements IComparator{
    
    /**
     * The field to compare
     * @var string
     * @since 1.0-sofia
     */
    protected $field;
    
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
     * @param array|object $one The first object to compare
     * @param array|object $two The second object to compare
     * @return integer Returns -1 if $a < $b, 1 if $a > $b or 0 otherwise.
     * @since 1.0-sofia
     */
    function compare($one, $two) {
        $resultOne = $this->access($one);
        $resultTwo = $this->access($two);
        if ($resultOne == $resultTwo) {
            return 0;
        }
        return ($resultOne < $resultTwo) ? -1 : 1;
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