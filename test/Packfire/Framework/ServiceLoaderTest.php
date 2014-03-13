<?php

namespace Packfire\Framework;

use PHPUnit_Framework_TestCase;
use Packfire\FuelBlade\Container;

class ServiceLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testGetContainer2()
    {
        $container = new Container();
        $loader = new ServiceLoader($container);
        $this->assertInstanceOf('Packfire\\FuelBlade\\ContainerInterface', $loader->getContainer());
        $this->assertEquals($container, $loader->getContainer());
    }

    public function testLoad()
    {
        $container = new Container();
        $loader = new ServiceLoader($container);
        $container['Packfire\\Framework\\Package\\LoaderInterface'] = $loader;
        $loader->load();
        $this->assertInstanceOf('Psr\\Log\\LoggerInterface', $container['Psr\\Log\\LoggerInterface']);
    }
}
