<?php
namespace Packfire\Net\Http;

/**
 * Test class for ClientOS.
 * Generated by PHPUnit on 2012-09-17 at 07:59:42.
 */
class ClientOSTest extends \PHPUnit_Framework_TestCase {

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

    public function testConstants() {
        $this->assertEquals('Android', ClientOS::ANDROID);
        $this->assertEquals('BingBot', ClientOS::BINGBOT);
        $this->assertEquals('Blackberry', ClientOS::BLACKBERRY);
        $this->assertEquals('Googlebot', ClientOS::GOOGLEBOT);
        $this->assertEquals('iOS', ClientOS::IOS);
        $this->assertEquals('Linux', ClientOS::LINUX);
        $this->assertEquals('Macintosh', ClientOS::MACINTOSH);
        $this->assertEquals('MSNBot', ClientOS::MSNBOT);
        $this->assertEquals('UNIX', ClientOS::UNIX);
        $this->assertEquals('Windows', ClientOS::WINDOWS);
        $this->assertEquals('Yahoo! Slurp', ClientOS::YAHOOBOT);

        $this->assertEquals('', ClientOS::UNKNOWN);
    }

}