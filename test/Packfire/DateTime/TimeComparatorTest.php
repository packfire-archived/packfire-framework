<?php

namespace Packfire\DateTime;

/**
 * Test class for TimeComparator.
 * Generated by PHPUnit on 2012-10-12 at 08:53:15.
 */
class TimeComparatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\DateTime\TimeComparator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new TimeComparator();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\DateTime\TimeComparator::compare
     */
    public function testCompare()
    {
        $this->assertInstanceOf('\\Packfire\\Collection\\Sort\\Comparator\\PropertyComparator', $this->object);

        $time1 = new Time(8, 40, 10, 100);
        $time2 = new Time(16, 54, 6, 350);
        $time3 = new Time(16, 54, 6, 350);
        $this->assertEquals(1, $this->object->compare($time1, $time2));
        $this->assertEquals(-1, $this->object->compare($time2, $time1));
        $this->assertEquals(0, $this->object->compare($time2, $time3));
    }

}
