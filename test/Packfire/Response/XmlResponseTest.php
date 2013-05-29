<?php

namespace Packfire\Response;

/**
 * Test class for XmlResponse.
 * Generated by PHPUnit on 2012-06-18 at 02:09:49.
 */
class XmlResponseTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @covers \Packfire\Response\XmlResponse
     */
    public function testResponse()
    {
        $data = array(
            'data' => 5
        );
        $object = new XmlResponse($data);
        $this->assertEquals('application/xml', $object->headers()->get('Content-Type'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<root><data type="integer">5</data></root>', $object->body());
        $this->assertEquals($object->body(), $object->output());
    }

    /**
     * @covers \Packfire\Response\XmlResponse
     */
    public function testResponse2()
    {
        $object = new XmlResponse('<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<root><data type="integer">5</data></root>');
        $this->assertEquals('application/xml', $object->headers()->get('Content-Type'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<root><data type="integer">5</data></root>', $object->body());
        $this->assertEquals($object->body(), $object->output());
    }

}
