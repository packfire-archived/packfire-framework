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
        $bootstrap = new Bootstrap($container);
        $this->assertInstanceof('Packfire\\FuelBlade\\ContainerInterface', $bootstrap->getContainer());
        $this->assertEquals($container, $bootstrap->getContainer());
    }
}
