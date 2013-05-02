<?php
namespace Packfire\Yaml;

/**
 * Test class for YamlReference.
 * Generated by PHPUnit on 2012-09-30 at 01:30:49.
 */
class YamlReferenceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Packfire\Yaml\YamlReference
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new YamlReference(array('test' => 'hurray'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers \Packfire\Yaml\YamlReference::__get
     */
    public function test__get() {
        $this->assertEquals('hurray', $this->object->test);
        $this->assertNull($this->object->alpha);
    }

    /**
     * @covers \Packfire\Yaml\YamlReference::__set
     */
    public function test__set() {
        $this->object->alpha = 5;
        $this->assertEquals(5, $this->object->alpha);
        $this->assertEquals('hurray', $this->object->test);
        $this->assertEquals(array('test' => 'hurray', 'alpha' => 5),
                $this->object->map()->toArray());
    }

    /**
     * @covers \Packfire\Yaml\YamlReference::map
     */
    public function testMap() {
        $this->assertInstanceOf('Packfire\Collection\Map', $this->object->map());
        $this->assertEquals(array('test' => 'hurray'),
                $this->object->map()->toArray());
    }

    /**
     * @covers \Packfire\Yaml\YamlReference::offsetExists
     */
    public function testOffsetExists() {
        $this->assertFalse(empty($this->object));
    }

    /**
     * @covers \Packfire\Yaml\YamlReference::offsetGet
     */
    public function testOffsetGet() {
        $this->assertEquals('hurray', $this->object['test']);
        $this->assertNull($this->object['alpha']);
    }

    /**
     * @covers \Packfire\Yaml\YamlReference::offsetSet
     */
    public function testOffsetSet() {
        $this->assertNull($this->object['alpha']);
        $this->object['alpha'] = 5;
        $this->assertEquals(5, $this->object['alpha']);
        $this->assertEquals(array('test' => 'hurray', 'alpha' => 5),
                $this->object->map()->toArray());
    }

    /**
     * @covers \Packfire\Yaml\YamlReference::offsetUnset
     */
    public function testOffsetUnset() {
        unset($this->object['test']);
        $this->assertNull($this->object['test']);
        $this->assertNull($this->object->test);
    }

}
