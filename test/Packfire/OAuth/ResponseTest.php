<?php
namespace Packfire\OAuth;

/**
 * Test class for Response.
 * Generated by PHPUnit on 2012-09-26 at 08:34:03.
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\OAuth\Response
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Response;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\OAuth\Response::oauth
     */
    public function testOauth()
    {
        $this->assertNull($this->object->oauth(OAuth::CALLBACK));
        $this->object->oauth(OAuth::TOKEN, 'token');
        $this->assertEquals('token', $this->object->oauth(OAuth::TOKEN));
    }

    /**
     * @covers \Packfire\OAuth\Response::format
     */
    public function testFormat()
    {
        $this->object->oauth(OAuth::TOKEN, 'token');
        $context = $this->object->format('Packfire\Response\JsonResponse');
        $this->assertInstanceOf('Packfire\Response\JsonResponse', $context);
        /* @var $context IResponseFormat */
        $this->assertEquals(json_encode(array(OAuth::TOKEN => 'token')), $context->output());
    }

    /**
     * @covers \Packfire\OAuth\Response::body
     */
    public function testBody()
    {
        $this->object->oauth(OAuth::TOKEN, 'token');
        $this->object->oauth(OAuth::TOKEN_SECRET, 'secret');
        $this->assertEquals('oauth_token=token&oauth_token_secret=secret', $this->object->body());
    }

    /**
     * @covers \Packfire\OAuth\Response::output
     */
    public function testOutput()
    {
        $this->object->oauth(OAuth::TOKEN, 'token');
        $this->object->oauth(OAuth::TOKEN_SECRET, 'secret');
        $this->assertEquals($this->object->body(), $this->object->output());
    }

}
