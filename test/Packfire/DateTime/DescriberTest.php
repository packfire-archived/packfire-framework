<?php
namespace Packfire\DateTime;

class DescriberTest extends \PHPUnit_Framework_TestCase {
    
    private $object;
    
    protected function setUp(){
        $this->object = new Describer();
    }
    
    protected function tearDown(){
        
    }
    
    public function testDescribe(){
        $dt1 = new DateTime(2012, 10, 15, 12, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('5 mins', $this->object->describe($dt1, $dt2));
    }
    
    public function testDescribe2(){
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('4 days, 1 hour and 5 mins', $this->object->describe($dt1, $dt2));
    }
    
    public function testLimit(){
        $this->assertNull($this->object->limit());
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->object->limit(1);
        $this->assertEquals(1, $this->object->limit());
        $this->assertEquals('4 days', $this->object->describe($dt1, $dt2));
    }
    
    public function testLimit2(){
        $this->assertNull($this->object->limit());
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->object->limit(2);
        $this->assertEquals(2, $this->object->limit());
        $this->assertEquals('4 days and 1 hour', $this->object->describe($dt1, $dt2));
    }
    
    public function testListing(){
        $this->assertTrue($this->object->listing());
        $this->object->listing(false);
        $this->assertFalse($this->object->listing());
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('4 days 1 hour 5 mins', $this->object->describe($dt1, $dt2));
    }
}
