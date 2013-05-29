<?php

namespace Packfire\Collection;

/**
 * Test class for Queue.
 * Generated by PHPUnit on 2012-02-19 at 05:16:55.
 */
class QueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Collection\Queue
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Queue();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Collection\Queue::enqueue
     */
    public function testEnqueue()
    {
        $this->assertCount(0, $this->object);
        $this->object->enqueue(5);
        $this->assertCount(1, $this->object);
        $this->assertEquals(5, $this->object->front());
        $this->object->enqueue(2);
        $this->assertCount(2, $this->object);
        $this->assertEquals(5, $this->object->front());
    }

    /**
     * @covers \Packfire\Collection\Queue::dequeue
     */
    public function testDequeue()
    {
        $this->object->enqueue(5);
        $this->assertCount(1, $this->object);
        $this->object->enqueue(2);
        $this->assertCount(2, $this->object);
        $this->assertEquals(5, $this->object->dequeue());
        $this->assertCount(1, $this->object);
        $this->assertEquals(2, $this->object->dequeue());
        $this->assertCount(0, $this->object);
    }

    /**
     * @covers \Packfire\Collection\Queue::front
     */
    public function testFront()
    {
        $this->object->enqueue(5);
        $this->assertEquals(5, $this->object->front());
        $this->object->enqueue(2);
        $this->assertEquals(5, $this->object->front());
    }

    /**
     * @covers \Packfire\Collection\Queue::front
     */
    public function testFront2()
    {
        $this->assertEquals(null, $this->object->front());
    }
}
