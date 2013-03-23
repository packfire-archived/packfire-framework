<?php
namespace Packfire\Response;

use Packfire\Net\Http\ResponseCode;

/**
 * Test class for RedirectResponse.
 * Generated by PHPUnit on 2012-06-18 at 02:13:45.
 */
class RedirectResponseTest extends \PHPUnit_Framework_TestCase {

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
     * @covers \Packfire\Response\RedirectResponse
     */
    public function testResponse() {
        $url = 'http://example.com/';
        $object = new RedirectResponse($url);
        $this->assertEquals(ResponseCode::HTTP_302, $object->code());
        $this->assertEquals($url, $object->headers()->get('Location'));
        $this->assertEquals('', $object->body());
        $this->assertEquals('', $object->output());
    }

    /**
     * @covers \Packfire\Response\RedirectResponse
     */
    public function testResponse2() {
        $url = 'http://example.com/';
        $object = new RedirectResponse($url, ResponseCode::HTTP_301);
        $this->assertEquals(ResponseCode::HTTP_301, $object->code());
        $this->assertEquals($url, $object->headers()->get('Location'));
        $this->assertEquals('', $object->body());
        $this->assertEquals('', $object->output());
    }

}