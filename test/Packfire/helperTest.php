<?php

/**
 * Test class for helper class.
 */
class helperTest extends PHPUnit_Framework_TestCase {

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * @covers ::using
     */
    public function testUsing(){
        $alpha = 'test';
        $beta = 'unique';
        $this->assertEquals(5, using(function(){return 5;}));
        $this->assertEquals($alpha, using($alpha, function($v){return $v;}));
        $this->assertEquals($alpha . $beta, using($alpha,
                function($v) use ($beta){return $v . $beta;}));
        $this->assertEquals($alpha . $beta, using($alpha, $beta,
                function($a, $b){return $a . $b;}));
        $this->assertEquals(30, using(5, 6,
                function($a, $b){return $a * $b;}));
    }
    
    /**
     * @covers ::dtype
     */
    public function testDtype(){
        $this->assertEquals('integer', dtype(5));
        $this->assertEquals('boolean', dtype(false));
        $this->assertEquals('stdClass/', substr(dtype(new stdClass), 0, 9));
        $this->assertEquals('resource/', substr(dtype(fopen(__FILE__, 'r')), 0, 9));
    }

    
}