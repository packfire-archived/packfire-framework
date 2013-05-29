<?php
namespace Packfire\Template;

/**
 * Test class for Template.
 * Generated by PHPUnit on 2012-02-15 at 17:30:46.
 */
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Template\Template
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Template('{name}: {job} {date}');
    }

    public function testConstants()
    {
        $this->assertEquals('}', Template::KEY_CLOSE);
        $this->assertEquals('{', Template::KEY_OPEN);
    }
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Template\Template::fields
     */
    public function testFields()
    {
        $this->assertInstanceOf('Packfire\Collection\Map', $this->object->fields());
        $this->object->fields()->add('name', 'David HasHunch');
        $this->object->fields()->add('job', 'Scientist');
        $this->object->fields()->add('date', 'Monday');
        $this->assertInstanceOf('Packfire\Collection\Map', $this->object->fields());
    }

    /**
     * @covers \Packfire\Template\Template::parse
     */
    public function testParse()
    {
        $this->object->fields()->add('name', 'David HasHunch');
        $this->object->fields()->add('job', 'Scientist');
        $this->object->fields()->add('date', 'Monday');
        $result = $this->object->parse();
        $this->assertInternalType('string', $result);
        $this->assertEquals('David HasHunch: Scientist Monday', $result);
    }

}
