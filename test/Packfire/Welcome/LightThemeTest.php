<?php
namespace Packfire\Welcome;

/**
 * Test class for LightTheme.
 * Generated by PHPUnit on 2012-09-03 at 03:58:42.
 */
class LightThemeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Packfire\Welcome\LightTheme
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new LightTheme;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers \Packfire\Welcome\LightTheme::render
     */
    public function testRender() {
        $this->assertInstanceOf('Packfire\View\Theme', $this->object);
        $this->object->render();
        $this->assertCount(1, $this->object->fields());
        $this->assertEquals('light', $this->object->fields()->get('style'));
    }

}