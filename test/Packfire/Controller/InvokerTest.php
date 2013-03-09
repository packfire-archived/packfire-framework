<?php
namespace Packfire\Controller;

use Packfire\Application\Http\Request as HttpRequest;
use Packfire\Application\Http\Response as HttpResponse;
use Packfire\Collection\Map;
use Packfire\Session\Session;
use Packfire\Route\Http\Route;
use Packfire\Route\Http\Router;
require_once('test/Mocks/SessionStorage.php');

/**
 * Test class for Invoker.
 * Generated by PHPUnit on 2012-09-03 at 05:56:15.
 */
class InvokerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Invoker
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $request = new HttpRequest(null, null);
        $request->method('GET');
        $this->object = new Invoker(
                        'Packfire\Welcome\HomeController',
                        'index',
                        $request,
                        new Route('test', array()),
                        new HttpResponse()
        );
        $storage = new \Packfire\Test\Mocks\SessionStorage();
        $bucket->put('session.storage', $storage);
        $bucket->put('session', new Session($storage));
        $bucket->put('loader', $this->object);
        $router = new Router();
        $config = new Map(array('rewrite' => 'home/{theme}', 'actual' => 'Rest'));
        $router->add('home', new Route('route.home', $config));
        $router->add('themeSwitch', new Route('route.home', $config));
        $bucket->put('router', $router);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers Invoker::load
     */
    public function testLoad() {
        $this->assertTrue($this->object->load());
        $this->assertInstanceOf('Packfire\Application\IAppResponse', $this->object->response());
        $this->assertNotEmpty($this->object->response()->body());
    }

}