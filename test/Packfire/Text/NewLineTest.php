<?php
namespace Packfire\Text;

/**
 * Test class for NewLine.
 * Generated by PHPUnit on 2012-04-25 at 07:46:21.
 */
class NewLineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testConstants()
    {
        $this->assertEquals("\r\n", NewLine::WINDOWS);
        $this->assertEquals("\r", NewLine::MACINTOSH);
        $this->assertEquals("\n", NewLine::UNIX);
        $this->assertEquals('<br>', NewLine::HTML_BR);
        $this->assertEquals('<br />', NewLine::XHTML_BR);
    }

    /**
     * @covers \Packfire\Text\NewLine::neutralize
     */
    public function testNeutralizeSimple()
    {
        $this->assertEquals("Breaking\nDawn",
                NewLine::neutralize("Breaking\nDawn"));
        $this->assertEquals("Breaking\rDawn",
                NewLine::neutralize("Breaking\nDawn", NewLine::MACINTOSH));
        $this->assertEquals("Breaking\r\nDawn",
                NewLine::neutralize("Breaking\nDawn", NewLine::WINDOWS));
        $this->assertEquals('Breaking<br>Dawn',
                NewLine::neutralize("Breaking\nDawn", NewLine::HTML_BR));
        $this->assertEquals('Breaking<br />Dawn',
                NewLine::neutralize("Breaking\nDawn", NewLine::XHTML_BR));
        $this->assertEquals("Breaking-Dawn",
                NewLine::neutralize("Breaking\nDawn", '-'));
    }

    /**
     * @covers \Packfire\Text\NewLine::neutralize
     */
    public function testNeutralizeComplex()
    {
        $this->assertEquals("Breaking\n\n\nDawn",
                NewLine::neutralize("Breaking\n\r\n\nDawn"));
        $this->assertEquals("Breaking\r\r\rDawn",
                NewLine::neutralize("Breaking\n\r\n\nDawn", NewLine::MACINTOSH));
        $this->assertEquals("Breaking\r\n\r\n\r\nDawn",
                NewLine::neutralize("Breaking\n\r\n\nDawn", NewLine::WINDOWS));
    }

}
