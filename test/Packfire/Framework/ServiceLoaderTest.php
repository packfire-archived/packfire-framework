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
        $this->assertInstanceof('Packfire\\FuelBlade\\ContainerInterface', $loader->getContainer());
        $this->assertEquals($container, $loader->getContainer());
    }
}
