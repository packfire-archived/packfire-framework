<?php
namespace Packfire\Collection\Sort\Comparator;


/**
 * Test class for ObjectSelectedFieldComparator.
 * Generated by PHPUnit on 2012-09-21 at 13:01:59.
 */
class ObjectSelectedFieldComparatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Packfire\Collection\Sort\Comparator\ObjectSelectedFieldComparator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new ObjectSelectedFieldComparator(function($object){return $object->key;});
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers \Packfire\Collection\Sort\Comparator\ObjectFieldComparator::compare
     */
    public function testCompare() {
        $object1 = new \stdClass();
        $object1->key = 5;
        $object2 = new \stdClass();
        $object2->key = 6;
        $this->assertEquals(-1, $this->object->compare($object1, $object2));
        $this->assertEquals(0, $this->object->compare($object2, $object2));        
        $this->assertEquals(1, $this->object->compare($object2, $object1));        
    }

}
