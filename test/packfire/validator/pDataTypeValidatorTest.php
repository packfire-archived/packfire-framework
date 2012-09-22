<?php

pload('packfire.validator.pDataTypeValidator');

/**
 * Test class for pDataTypeValidator.
 * Generated by PHPUnit on 2012-09-22 at 14:47:52.
 */
class pDataTypeValidatorTest extends PHPUnit_Framework_TestCase {

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
     * @covers pDataTypeValidator::validate
     */
    public function testValidate() {
        $object = new pDataTypeValidator('integer');
        $this->assertTrue($object->validate(5));
        $this->assertTrue($object->validate(2));
        $this->assertFalse($object->validate('test'));
        $this->assertFalse($object->validate('8'));
    }

    /**
     * @covers pDataTypeValidator::validate
     */
    public function testValidate1() {
        $object = new pDataTypeValidator('stdClass');
        $this->assertTrue($object->validate(new stdClass()));
        $this->assertFalse($object->validate('test'));
    }

}