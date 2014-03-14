<?php

namespace Packfire\Framework;

use PHPUnit_Framework_TestCase;
use Packfire\FuelBlade\Container;

class BootstrapTest extends PHPUnit_Framework_TestCase
{
    public function testGetContainer()
    {
        $bootstrap = new Bootstrap();
        $this->assertInstanceof('Packfire\\FuelBlade\\ContainerInterface', $bootstrap->getContainer());
    }

    public function testGetContainer2()
    {
        $container = new Container();
        $bootstrap = new Bootstrap(null, $container);
        $this->assertInstanceof('Packfire\\FuelBlade\\ContainerInterface', $bootstrap->getContainer());
        $this->assertEquals($container, $bootstrap->getContainer());
    }

    public function testBootPath()
    {
        $bootstrap = new Bootstrap();
        $this->assertEquals(pathinfo(__FILE__, PATHINFO_DIRNAME), $bootstrap->bootPath());
    }

    public function testRun()
    {
        $bootstrap = new Bootstrap();
        $container = $bootstrap->getContainer();
        $configManager = $this->getMock('Packfire\\Framework\\Package\\ConfigManagerInterface');
        $configManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('routes'))
            ->will($this->returnValue($this->getMock('Packfire\\Config\\ConfigInterface')));
        $container['Packfire\\Framework\\Package\\ConfigManagerInterface'] = $configManager;
        $this->assertFalse(isset($container['Packfire\\Router\\RouterInterface']));
        $this->setExpectedException('Packfire\\Framework\\Exceptions\\RouteNotFoundException');
        $bootstrap->run();
        $this->assertInstanceOf('Packfire\\Router\\RouterInterface', $container['Packfire\\Router\\RouterInterface']);
        $this->assertInstanceOf('Packfire\\Framework\\Package\\LoaderInterface', $container['Packfire\\Framework\\Package\\LoaderInterface']);
    }

    protected function createMockRouter()
    {
        $route = $this->getMock('Packfire\\Router\\RouteInterface');
        $route->expects($this->once())
            ->method('execute');

        $router = $this->getMock('Packfire\\Router\\RouterInterface');
        $router->expects($this->any())
            ->method('route')
            ->with($this->isInstanceOf('Packfire\\Router\\RequestInterface'))
            ->will($this->returnValue($route));

        return $router;
    }

    public function testRun2()
    {
        $bootstrap = new Bootstrap();
        $container = $bootstrap->getContainer();

        $container['Packfire\\Router\\RouterInterface'] = $this->createMockRouter();
        $bootstrap->run();
    }

    public function testLoadPackage()
    {
        $bootstrap = new Bootstrap(__DIR__ . '/../../testPackages/package1');
        $container = $bootstrap->getContainer();

        $container['Packfire\\Router\\RouterInterface'] = $this->createMockRouter();

        $bootstrap->run();

        $config = $container['Packfire\\Framework\\Package\\ConfigManagerInterface'];
        $this->assertInstanceOf('Packfire\\Session\\StorageInterface', $container['Packfire\\Session\\StorageInterface']);
        $this->assertInstanceOf('Packfire\\Framework\\Package\\ConfigManagerInterface', $config);
        $this->assertEquals('192.168.3.52', $config['test']->get('database', 'users', 'host'));
        $this->assertEquals('localhost', $config['test']->get('database', 'default', 'host'));
    }
}
