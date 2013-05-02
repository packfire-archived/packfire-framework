<?php
namespace Packfire\Response;

/**
 * Test class for JsonResponse.
 * Generated by PHPUnit on 2012-06-18 at 02:17:03.
 */
class JsonResponseTest extends \PHPUnit_Framework_TestCase {

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
     * @covers \Packfire\Response\JsonResponse
     */
    public function testResponse() {
        $data = array(
            'data' => 5
        );
        $object = new JsonResponse($data);
        $this->assertEquals('application/json', $object->headers()->get('Content-Type'));
        $this->assertEquals(json_encode($data), $object->body());
        $this->assertEquals($object->body(), $object->output());
    }

    /**
     * @covers \Packfire\Response\JsonResponse
     */
    public function testResponse2() {
        $data = array(
            'data' => 5
        );
        $object = new JsonResponse($data, 'test');
        $this->assertEquals('text/javascript', $object->headers()->get('Content-Type'));
        $this->assertEquals('test(' . json_encode($data) . ')', $object->body());
        $this->assertEquals($object->body(), $object->output());
    }

}