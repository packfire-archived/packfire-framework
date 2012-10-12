<?php
namespace Packfire\Config\Driver;

/**
 * Test class for YamlConfig.
 * Generated by PHPUnit on 2012-07-14 at 06:11:25.
 */
class YamlConfigTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var YamlConfig
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new YamlConfig('test/Files/sampleConfig.yml');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    public function testConfigParse(){
        $this->assertNotNull($this->object->get('first_section'));
        $this->assertNotNull($this->object->get('second_section'));
        $this->assertNotNull($this->object->get('third_section'));
        $this->assertCount(3, $this->object->get());

        $this->assertEquals(1, $this->object->get('first_section', 'one'));
        $this->assertEquals('BIRD', $this->object->get('first_section', 'animal'));

        $this->assertEquals(array(
            'path' => '/usr/local/bin',
            'URL' => 'http://www.example.com/~username'
        ), $this->object->get('second_section'));
    }

}