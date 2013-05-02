<?php
namespace Packfire\Validator;

use Packfire\Collection\ArrayList;

/**
 * Test class for MatchValidator.
 * Generated by PHPUnit on 2012-09-22 at 14:41:07.
 */
class MatchValidatorTest extends \PHPUnit_Framework_TestCase {

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
     * @covers \Packfire\Validator\MatchValidator::validate
     */
    public function testValidate() {
        $object = new MatchValidator(array('a', 'b', 'c'));
        $this->assertTrue($object->validate('b'));
        $this->assertTrue($object->validate('c'));
        $this->assertFalse($object->validate('z'));
        $this->assertFalse($object->validate('h'));
    }

    /**
     * @covers \Packfire\Validator\MatchValidator::validate
     */
    public function testValidate2() {
        $object = new MatchValidator(new ArrayList(array('a', 'b', 'c')));
        $this->assertTrue($object->validate('b'));
        $this->assertTrue($object->validate('c'));
        $this->assertFalse($object->validate('z'));
        $this->assertFalse($object->validate('h'));
    }

    /**
     * @covers \Packfire\Validator\MatchValidator::validate
     */
    public function testValidate3() {
        $object = new MatchValidator(5);
        $this->assertTrue($object->validate(5));
        $this->assertFalse($object->validate(2));
    }

}
