<?php
namespace Packfire\Collection;

/**
 * Test class for KeyValuePair.
 * Generated by PHPUnit on 2012-09-21 at 13:27:26.
 */
class KeyValuePairTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var KeyValuePair
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new KeyValuePair('test', 'value');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers KeyValuePair::key
     */
    public function testKey() {
        $this->assertEquals('test', $this->object->key());
        $this->object->key('key');
        $this->assertEquals('key', $this->object->key());
    }

    /**
     * @covers KeyValuePair::value
     */
    public function testValue() {
        $this->assertEquals('value', $this->object->value());
        $this->object->value('hey');
        $this->assertEquals('hey', $this->object->value());
    }

}