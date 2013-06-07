<?php
namespace Packfire\Collection\Sort\Comparator;

/**
 * Test class for PropertyComparator
 */
class PropertyComparatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Collection\Sort\Comparator\PropertyComparator
     */
    protected $object;

    protected $method;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('Packfire\\Collection\\Sort\\Comparator\\PropertyComparator');

        $this->method = new \ReflectionMethod($this->object, 'compareComponents');
        $this->method->setAccessible(true);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Collection\Sort\Comparator\PropertyComparator::compareComponents
     * @covers \Packfire\Collection\Sort\Comparator\PropertyComparator::compareComponent
     */
    public function testCompareComponents()
    {
        $components = array('comp1', 'comp2');
        $object1 = $this->getMock('\\stdClass', $components);
        $object1->expects($this->once())
            ->method('comp1')
            ->will($this->returnValue(5));
        $object1->expects($this->once())
            ->method('comp2')
            ->will($this->returnValue(2));

        $object2 = $this->getMock('\\stdClass', $components);
        $object2->expects($this->once())
            ->method('comp1')
            ->will($this->returnValue(5));
        $object2->expects($this->once())
            ->method('comp2')
            ->will($this->returnValue(7));

        $this->assertEquals(1, $this->method->invoke($this->object, $object1, $object2, $components));
    }
}
