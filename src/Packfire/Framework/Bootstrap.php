<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework;

use Packfire\FuelBlade\Container;
use Packfire\FuelBlade\ContainerInterface;

class Bootstrap
{
    protected $container;

    protected $bootPath;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ? $container : $this->createContainer();

        $backtrace = debug_backtrace();
        $this->bootPath = $backtrace[0]['file'];
    }

    protected function createContainer()
    {
        $container = new Container();
        $container['Packfire\\FuelBlade\\ContainerInterface'] = $container;
        return $container;
    }

    public function run()
    {
        $this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'] = $this->container->instantiate('Packfire\\Framework\\Package\\ConfigManager');
        $this->container['Packfire\\Router\\RouterInterface'] = $this->container->instantiate('Packfire\\Router\\Router');
        $this->container['Packfire\\Framework\\Package\\LoaderInterface'] = $this->container->instantiate('Packfire\\Framework\\Package\\Loader');

        $this->container['Packfire\\Framework\\Package\\LoaderInterface']->load($this->bootPath);
    }

    public function bootPath()
    {
        return $this->bootPath;
    }

    public function getContainer()
    {
        return $this->container;
    }
}
