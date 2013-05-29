<?php
namespace Packfire\Route\Http;

use Packfire\Net\Http\Method;
use Packfire\Collection\Map;
require_once 'test/Mocks/RouteRequest.php';
use Packfire\Test\Mocks\RouteRequest;

/**
 * Test class for Route.
 * Generated by PHPUnit on 2012-09-13 at 10:19:12.
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Route\Http\Route
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $config = new Map(array(
            'rewrite' => '/home/{data}/{name}',
            'actual' => 'Rest',
            'method' => array('delete'),
            'params' => array('data' => 'int', 'name' => 'alnum'),
            'remap' => array('data', 'object' => array('name'))
        ));
        $this->object = new Route('test', $config);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Route\Http\Route::name
     */
    public function testName()
    {
        $this->assertEquals('test', $this->object->name());
    }

    /**
     * @covers \Packfire\Route\Http\Route::httpMethod
     */
    public function testHttpMethod()
    {
        $this->assertEquals(array('delete'), $this->object->httpMethod());
    }

    /**
     * @covers \Packfire\Route\Http\Route::rewrite
     */
    public function testRewrite()
    {
        $this->assertEquals('/home/{data}/{name}', $this->object->rewrite());
    }

    /**
     * @covers \Packfire\Route\Http\Route::actual
     */
    public function testActual()
    {
        $this->assertEquals('Rest', $this->object->actual());
    }

    /**
     * @covers \Packfire\Route\Http\Route::params
     */
    public function testParams()
    {
        $this->assertEquals(array('data' => 'int', 'name' => 'alnum'),
                $this->object->rules()->toArray());
    }

    /**
     * @covers \Packfire\Route\Http\Route::match
     */
    public function testMatch()
    {
        $request = new RouteRequest('home/200/test',
                array('PHP_SELF' => 'index.php/home/200/test', 'SCRIPT_NAME' => 'index.php'));
        $this->assertFalse($this->object->match($request));
    }

    /**
     * @covers \Packfire\Route\Http\Route::match
     */
    public function testMatch2()
    {
        $request = new RouteRequest('home/200/jack',
                array('PHP_SELF' => 'index.php/home/200/jack', 'SCRIPT_NAME' => 'index.php'));
        $request->method(Method::DELETE);
        $this->assertTrue($this->object->match($request));
    }

    /**
     * @covers \Packfire\Route\Http\Route::match
     */
    public function testMatchVarFail()
    {
        $request = new RouteRequest('home/20.!0',
                array('PHP_SELF' => 'index.php/home/20.!0', 'SCRIPT_NAME' => 'index.php'));
        $request->method(Method::POST);
        $request->headers()->add('X-HTTP-Method-Override', Method::DELETE);
        $this->assertFalse($this->object->match($request));
    }

}
