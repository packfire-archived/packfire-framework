<?php
namespace Packfire\DateTime;

class DescriberTest extends \PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var \Packfire\DateTime\Describer
     */
    private $object;
    
    /**
     * @covers \Packfire\DateTime\Describer::__construct
     */
    protected function setUp(){
        $this->object = new Describer();
    }
    
    protected function tearDown(){
        
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testDescribe(){
        $dt1 = new DateTime(2012, 10, 15, 12, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('5 mins', $this->object->describe($dt1, $dt2));
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testDescribe2(){
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('4 days, 1 hour and 5 mins', $this->object->describe($dt1, $dt2));
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testDescribeNegative(){
        $dt2 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt1 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('4 days, 1 hour and 5 mins', $this->object->describe($dt1, $dt2));
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testQuantify(){
        $this->assertTrue($this->object->quantify());
        $this->object->quantify(false);
        $dt1 = new DateTime(2012, 10, 11, 9, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('4 day, 3 hour and 5 min', $this->object->describe($dt1, $dt2));
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testLimit(){
        $this->assertNull($this->object->limit());
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->object->limit(1);
        $this->assertEquals(1, $this->object->limit());
        $this->assertEquals('4 days', $this->object->describe($dt1, $dt2));
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testLimit2(){
        $this->assertNull($this->object->limit());
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->object->limit(2);
        $this->assertEquals(2, $this->object->limit());
        $this->assertEquals('4 days and 1 hour', $this->object->describe($dt1, $dt2));
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testListing(){
        $this->assertTrue($this->object->listing());
        $this->object->listing(false);
        $this->assertFalse($this->object->listing());
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('4 days 1 hour 5 mins', $this->object->describe($dt1, $dt2));
    }
    
    /**
     * @covers \Packfire\DateTime\Describer::describe
     */
    public function testAdjectives(){
        $this->assertCount(6, $this->object->adjectives());
        $this->object->quantify(false);
        $this->object->adjectives(array('day' => 'hari', 'hour' => 'jam', 'minute' => 'minit', 'second' => 'saat', 'and' => 'dan'));
        $dt1 = new DateTime(2012, 10, 11, 11, 30, 00);
        $dt2 = new DateTime(2012, 10, 15, 12, 35, 00);
        $this->assertEquals('4 hari, 1 jam dan 5 minit', $this->object->describe($dt1, $dt2));
    }
}
