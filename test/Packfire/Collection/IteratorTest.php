<?php

namespace Packfire\Collection;

/**
 * Test class for Iterator.
 * Generated by PHPUnit on 2012-04-27 at 00:51:25.
 */
class IteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $array;

    /**
     * @var \Packfire\Collection\Iterator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers \Packfire\Collection\Iterator::__construct
     */
    protected function setUp()
    {
        $this->array = array(1, 3, 5, 7, 9, 11, 13, 15);
        $this->object = new Iterator($this->array);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Collection\Iterator::iterate
     */
    public function testIterate()
    {
        $i = 0;

        while ($kvp = $this->object->iterate()) {
            $this->assertEquals($this->array[$i], $kvp->value());
            $this->assertEquals($i, $kvp->key());
            ++$i;
        }
    }

    /**
     * @covers \Packfire\Collection\Iterator::next
     */
    public function testNext()
    {
        $this->assertEquals(3, $this->object->next());
        $this->assertEquals(5, $this->object->next());
        $this->assertEquals(7, $this->object->next());
        $this->assertEquals(9, $this->object->next());
        $this->assertEquals(11, $this->object->next());
        $this->assertEquals(13, $this->object->next());
        $this->assertEquals(15, $this->object->next());
        $this->assertEquals(false, $this->object->next());
    }

    /**
     * @covers \Packfire\Collection\Iterator::more
     */
    public function testMore()
    {
        $mock = array();
        $this->assertTrue($this->object->more());
        do {
            $a = $this->object->current();
            $mock[] = $a;
        } while ($this->object->next());
        $this->assertFalse($this->object->more());
    }

    /**
     * @covers \Packfire\Collection\Iterator::current
     */
    public function testCurrent()
    {
        $this->assertEquals(1, $this->object->current());
        $this->object->next();
        $this->assertEquals(3, $this->object->current());
        $this->object->next();
        $this->assertEquals(5, $this->object->current());
    }

    /**
     * @covers \Packfire\Collection\Iterator::reset
     */
    public function testReset()
    {
        $this->assertEquals(1, $this->object->current());
        $this->object->next();
        $this->object->next();
        $this->object->reset();
        $this->assertEquals(1, $this->object->current());
    }

    /**
     * @covers \Packfire\Collection\Iterator::count
     */
    public function testCount()
    {
        $this->assertCount(8, $this->object);
        $this->assertEquals(8, $this->object->count());
    }

}
