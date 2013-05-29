<?php
namespace Packfire\Data\Serialization;

use Packfire\Text\TextStream;

/**
 * Test class for XmlSerializer.
 * Generated by PHPUnit on 2012-06-12 at 14:01:31.
 */
class XmlSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Data\Serialization\XmlSerializer
     */
    private $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new XmlSerializer();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Data\Serialization\XmlSerializer::serialize
     */
    public function testSerialize()
    {
        $data = array('key' => 'value', 'test');
        $stream = new TextStream();
        $this->object->serialize($stream, $data);
        $stream->seek(0);
        $this->assertEquals('<root><key>value</key><node key="0">test</node></root>', $stream->read($stream->length()));
    }

    /**
     * @covers \Packfire\Data\Serialization\XmlSerializer::serialize
     */
    public function testSerialize2()
    {
        $data = new \stdClass();
        $data->test = 'data';
        $data->control = array('date' => true);
        $stream = new TextStream();
        $this->object->serialize($stream, $data);
        $stream->seek(0);
        $this->assertEquals('<class.stdClass><test>data</test><control><date type="boolean">true</date></control></class.stdClass>', $stream->read($stream->length()));
    }

    /**
     * @covers \Packfire\Data\Serialization\XmlSerializer::serialize
     */
    public function testSerialize3()
    {
        $data = new \stdClass();
        $data->test = 'data';
        $data->control = array('date' => true);
        $obj = $this->object->serialize($data);
        $this->assertEquals('<class.stdClass><test>data</test><control><date type="boolean">true</date></control></class.stdClass>', $obj);
    }

    /**
     * @covers \Packfire\Data\Serialization\XmlSerializer::deserialize
     */
    public function testDeserialize()
    {
        $data = array('key' => 'value', 'test');
        $stream = new TextStream('<root><key>value</key><node key="0">test</node></root>');
        $item = $this->object->deserialize($stream);
        $this->assertEquals($data, $item);
    }

    /**
     * @covers \Packfire\Data\Serialization\XmlSerializer::deserialize
     */
    public function testDeserialize2()
    {
        $data = new \stdClass();
        $data->test = 'data';
        $data->control = array('date' => true);
        $stream = new TextStream('<class.stdClass><test>data</test><control><date type="boolean">true</date></control></class.stdClass>');
        $item = $this->object->deserialize($stream);
        $this->assertEquals($data, $item);
    }

    /**
     * @covers \Packfire\Data\Serialization\XmlSerializer::deserialize
     */
    public function testDeserialize3()
    {
        $data = new \stdClass();
        $data->test = 'data';
        $data->control = array('date' => true);
        $item = $this->object->deserialize('<class.stdClass><test>data</test><control><date type="boolean">true</date></control></class.stdClass>');
        $this->assertEquals($data, $item);
    }

    /**
     * @covers \Packfire\Data\Serialization\XmlSerializer::serialize
     * @covers \Packfire\Data\Serialization\XmlSerializer::deserialize
     */
    public function testOverall()
    {
        $data = new \stdClass();
        $data->test = 'data';
        $data->control = array('date' => true);
        $item = $this->object->deserialize($this->object->serialize($data));
        $this->assertEquals($data, $item);
    }

}
