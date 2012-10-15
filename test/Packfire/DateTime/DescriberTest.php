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
        $this->assertEquals('5 mins ago', $this->object->describe($dt1, $dt2));
    }
}
