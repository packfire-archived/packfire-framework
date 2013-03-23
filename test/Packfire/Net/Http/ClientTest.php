<?php
namespace Packfire\Net\Http;

/**
 * Test class for Client.
 * Generated by PHPUnit on 2012-09-17 at 08:03:54.
 */
class ClientTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Packfire\Net\Http\Client
     */
    protected $object;

    const IP = '192.168.1.2';

    const UA = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.29 Safari/525.13';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Client(self::IP, self::UA);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers \Packfire\Net\Http\Client::knownBots
     */
    public function testKnownBots() {
        $this->assertInternalType('array', Client::knownBots());
        $this->assertContains('googlebot', Client::knownBots());
    }

    /**
     * @covers \Packfire\Net\Http\Client::browserName
     */
    public function testBrowserName() {
        $this->assertEquals(ClientBrowser::CHROME, $this->object->browserName());
    }

    /**
     * @covers \Packfire\Net\Http\Client::browserVersion
     */
    public function testBrowserVersion() {
        $this->assertEquals('0.2.149.29', $this->object->browserVersion());
    }

    /**
     * @covers \Packfire\Net\Http\Client::operatingSystem
     */
    public function testOperatingSystem() {
        $this->assertEquals(ClientOS::WINDOWS, $this->object->operatingSystem());
    }

    /**
     * @covers \Packfire\Net\Http\Client::userAgent
     */
    public function testUserAgent() {
        $this->assertEquals(self::UA, $this->object->userAgent());
    }

    /**
     * @covers \Packfire\Net\Http\Client::ipAddress
     */
    public function testIpAddress() {
        $this->assertEquals(self::IP, $this->object->ipAddress());
    }

    /**
     * @covers \Packfire\Net\Http\Client::bot
     */
    public function testBot() {
        $this->assertFalse($this->object->bot());
    }

}
