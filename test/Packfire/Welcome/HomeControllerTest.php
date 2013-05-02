<?php
namespace Packfire\Welcome;

use Packfire\Application\Http\Response;
use Packfire\FuelBlade\Container;
use Packfire\Session\Session;
use Packfire\Route\Http\Route;
use Packfire\Route\Http\Router;
use Packfire\Collection\Map;
require_once('test/Mocks/SessionStorage.php');
use Packfire\Test\Mocks\SessionStorage;

/**
 * Test class for HomeController.
 * Generated by PHPUnit on 2012-09-09 at 02:29:53.
 */
class HomeControllerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Packfire\Welcome\HomeController
     */
    protected $object;
    
    private $ioc;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new HomeController();
        $this->ioc = new Container();
        $bucket = $this->ioc;
        $bucket['session.storage'] = new SessionStorage();
        $bucket['session'] = $bucket->share(function($c){
            return new Session($c['session.storage']);
        });
        $router = new Router();
        $config = new Map(array('rewrite' => 'home/{theme}', 'actual' => 'Rest'));
        $router->add('home', new Route('route.home', $config));
        $router->add('themeSwitch', new Route('route.home', $config));
        
        $bucket['router'] = $router;
        $bucket['route'] = $router->entries()->get('home');
        
        $bucket['response'] = new Response();
        
        call_user_func($this->object, $this->ioc);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers \Packfire\Welcome\HomeController::message
     */
    public function testMessage() {
        $this->object->message();
        $this->assertTrue(array_key_exists('title', $this->object->state()));
        $this->assertTrue(array_key_exists('message', $this->object->state()));
    }

    /**
     * @covers \Packfire\Welcome\HomeController::getIndex
     */
    public function testGetIndex() {
        $this->object->getIndex();
        $this->assertInstanceOf('Packfire\Application\Http\Response', $this->ioc['response']);
        $this->assertEquals('<!DOCTYPE html>', substr($this->ioc['response']->body(),0,15));
    }

    /**
     * @covers \Packfire\Welcome\HomeController::cliIndex
     */
    public function testCliIndex() {
        ob_start();
        $this->object->cliIndex();
        $contents = ob_get_contents();
        $this->assertNotEmpty($contents);
        ob_end_clean();
    }

}