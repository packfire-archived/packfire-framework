<?php

namespace Packfire\Text;

/**
 * Test class for TextStream.
 * Generated by PHPUnit on 2012-04-28 at 02:32:16.
 */
class TextStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Text\TextStream
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new TextStream('The quick brown fox jumps over the lazy dog.');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Text\TextStream::open
     * @covers \Packfire\Text\TextStream::close
     */
    public function testOpenClose()
    {
        $this->assertTrue(method_exists($this->object, 'open'));
        $this->assertTrue(method_exists($this->object, 'close'));
    }

    public function testInheritance()
    {
        $this->assertInstanceOf('Packfire\IO\IStream', $this->object);
    }

    /**
     * @covers \Packfire\Text\TextStream::length
     */
    public function testLength()
    {
        $this->assertEquals(44, $this->object->length());
    }

    /**
     * @covers \Packfire\Text\TextStream::length
     */
    public function testLength2()
    {
        $s = 'I am sam, here we moo';
        $obj = new TextStream($s);
        $this->assertEquals(strlen($s), $obj->length());
    }

    /**
     * @covers \Packfire\Text\TextStream::read
     */
    public function testRead()
    {
        $str = $this->object->read(3);
        $this->assertEquals('The', $str);
        $str = $this->object->read(6);
        $this->assertEquals(' quick', $str);
        $str = $this->object->read(40);
        $this->assertEquals(' brown fox jumps over the lazy dog.', $str);
    }

    /**
     * @covers \Packfire\Text\TextStream::seek
     */
    public function testSeek()
    {
        $this->object->seek(9);
        $this->assertEquals(9, $this->object->tell());
        $this->assertEquals(' br', $this->object->read(3));
    }

    /**
     * @covers \Packfire\Text\TextStream::seek
     * @expectedException \Packfire\Exception\OutOfRangeException
     */
    public function testSeek2()
    {
        $this->object->seek($this->object->length()+10);
    }

    /**
     * @covers \Packfire\Text\TextStream::seekable
     */
    public function testSeekable()
    {
        $this->assertTrue($this->object->seekable());
    }

    /**
     * @covers \Packfire\Text\TextStream::tell
     */
    public function testTell()
    {
        $this->assertEquals(0, $this->object->tell());
        $this->object->seek(5);
        $this->assertEquals(5, $this->object->tell());
        $this->object->read(10);
        $this->assertEquals(15, $this->object->tell());
        $this->object->write('written jones');
        $this->assertEquals(28, $this->object->tell());
        $this->object->write('written jones', 5);
        $this->assertEquals(18, $this->object->tell());
        $this->object->write('written jones', 2, 6);
        $this->assertEquals(8, $this->object->tell());
    }

    /**
     * @covers \Packfire\Text\TextStream::write
     */
    public function testWrite()
    {
        $s = 'Run apple. ';
        $this->object->write($s);
        $this->object->seek(0);
        $this->assertEquals($s, $this->object->read(11));
    }

    /**
     * @covers \Packfire\Text\TextStream::write
     */
    public function testWrite2()
    {
        $s = 'Run apple. ';
        $this->object->write($s, 5);
        $this->object->seek(5);
        $this->assertEquals($s, $this->object->read(11));
        $this->assertEquals(55, $this->object->length());
    }

    /**
     * @covers \Packfire\Text\TextStream::write
     */
    public function testWrite3()
    {
        $s = 'Run apple. ';
        $this->object->write($s, 5, 3);
        $this->object->seek(5);
        $this->assertEquals('Runk brown', $this->object->read(10));
        $this->assertEquals(44, $this->object->length());
    }

}
