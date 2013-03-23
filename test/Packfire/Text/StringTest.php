<?php

namespace Packfire\Text;

/**
 * Test class for String.
 * Generated by PHPUnit on 2012-04-25 at 07:54:14.
 */
class StringTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var array
     */
    protected $rawString = array(
        'testing same thing over and over again  ',
        " \t killjoys         ",
        'Somewhere OVER THE RAINBOW!'
    );

    /**
     * @var array
     */
    protected $objects;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->objects = array();
        foreach ($this->rawString as $str) {
            $this->objects[] = new String($str);
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * @covers \Packfire\Text\String::from
     */
    public function testFrom(){
        $obj = String::from($this->rawString[0]);
        $this->assertInstanceOf('Packfire\Text\String', $obj);
        $this->assertEquals($this->rawString[0], $obj->value());
    }
    
    /**
     * @covers \Packfire\Text\String::format
     */
    public function testFormat(){
        $object = String::from('test%d');
        /* @var $object \Packfire\Text\String */
        $result = $object->format(10);
        $this->assertEquals('test10', $result->value());
    }

    /**
     * @covers \Packfire\Text\String::value
     */
    public function testValue() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals($this->rawString[$idx], $obj->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::trim
     */
    public function testTrim() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(trim($this->rawString[$idx]), $obj->trim()->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::trimLeft
     */
    public function testTrimLeft() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(ltrim($this->rawString[$idx]), $obj->trimLeft()->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::trimRight
     */
    public function testTrimRight() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(rtrim($this->rawString[$idx]), $obj->trimRight()->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::split
     */
    public function testSplit() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $a = $obj->split(' ')->toArray();
            $this->assertEquals(explode(' ', $this->rawString[$idx]), $a);
        }
    }

    /**
     * @covers \Packfire\Text\String::replace
     */
    public function testReplace() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $result = $obj->replace('over', 'time');
            $this->assertEquals(str_replace('over', 'time', $this->rawString[$idx]), $result);
        }
    }

    /**
     * @covers \Packfire\Text\String::indexOf
     */
    public function testIndexOf() {
        $this->assertEquals(4, $this->objects[0]->indexOf('ing'));
        $this->assertEquals(8, $this->objects[0]->indexOf('same'));
        $this->assertEquals(19, $this->objects[0]->indexOf('over'));
        $this->assertEquals(-1, $this->objects[0]->indexOf('nil'));
    }

    /**
     * @covers \Packfire\Text\String::lastIndexOf
     */
    public function testLastIndexOf() {
        $this->assertEquals(15, $this->objects[0]->lastIndexOf('ing'));
        $this->assertEquals(36, $this->objects[0]->lastIndexOf('in'));
        $this->assertEquals(0, $this->objects[0]->lastIndexOf('test'));
        $this->assertEquals(-1, $this->objects[0]->lastIndexOf('nil'));
    }

    /**
     * @covers \Packfire\Text\String::occurances
     */
    public function testOccurances() {
        $this->assertCount(2, $this->objects[0]->occurances('over'));
        $this->assertCount(1, $this->objects[1]->occurances('joy'));
        $this->assertCount(0, $this->objects[1]->occurances('tan'));
    }

    /**
     * @covers \Packfire\Text\String::substring
     */
    public function testSubstring() {
        $this->assertEquals('test', $this->objects[0]->substring(0, 4));
        $this->assertEquals('same', $this->objects[0]->substring(8, 4));
    }

    /**
     * @covers \Packfire\Text\String::toUpper
     */
    public function testToUpper() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(strtoupper($this->rawString[$idx]), $obj->toUpper()->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::toLower
     */
    public function testToLower() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(strtolower($this->rawString[$idx]), $obj->toLower()->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::padLeft
     */
    public function testPadLeft() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(str_pad($this->rawString[$idx], 5, ' ', STR_PAD_LEFT), $obj->padLeft(' ', 5)->value());
            $this->assertEquals(str_pad($this->rawString[$idx], 5, '-', STR_PAD_LEFT), $obj->padLeft('-', 5)->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::padRight
     */
    public function testPadRight() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(str_pad($this->rawString[$idx], 5, ' ', STR_PAD_RIGHT), $obj->padRight(' ', 5)->value());
            $this->assertEquals(str_pad($this->rawString[$idx], 5, '-', STR_PAD_RIGHT), $obj->padRight('-', 5)->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::padBoth
     */
    public function testPadBoth() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(str_pad($this->rawString[$idx], 5, ' ', STR_PAD_BOTH), $obj->padBoth(' ', 5)->value());
            $this->assertEquals(str_pad($this->rawString[$idx], 5, '-', STR_PAD_BOTH), $obj->padBoth('-', 5)->value());
        }
    }

    /**
     * @covers \Packfire\Text\String::length
     */
    public function testLength() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(strlen($this->rawString[$idx]), $obj->length());
            $this->assertEquals(strlen(trim($this->rawString[$idx])), $obj->trim()->length());
        }
    }

    /**
     * @covers \Packfire\Text\String::count
     */
    public function testCount() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals(strlen($this->rawString[$idx]), $obj->count());
            $this->assertEquals(strlen(trim($this->rawString[$idx])), $obj->trim()->count());
        }
    }

    /**
     * @covers \Packfire\Text\String::__toString
     */
    public function test__toString() {
        foreach ($this->objects as $idx => $obj) {
            /* @var $obj String */
            $this->assertEquals($this->rawString[$idx], $obj->__toString());
            $this->assertEquals($this->rawString[$idx], (string) $obj);
        }
    }

}