<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework;

use Packfire\FuelBlade\Container;
use Packfire\FuelBlade\ContainerInterface;
use Packfire\Router\ConfigLoader;
use Packfire\Router\CurrentRequest;
use Packfire\Framework\Exceptions\RouteNotFoundException;

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
        if (!isset($this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'])) {
            $this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'] = $this->container->instantiate('Packfire\\Framework\\Package\\ConfigManager');
        }
        if (!isset($this->container['Packfire\\Framework\\Package\\LoaderInterface'])) {
            $this->container['Packfire\\Framework\\Package\\LoaderInterface'] = $this->container->instantiate('Packfire\\Framework\\Package\\Loader');
        }

        $this->loadPackage();

        if (!isset($this->container['Packfire\\Router\\RouterInterface'])) {
            $configManager = $this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'];
            $this->container['Packfire\\Router\\RouterInterface'] = $this->container->instantiate('Packfire\\Router\\ConfigLoader', array('config' => $configManager->get('routes')))->load();
        }

        $router = $this->container['Packfire\\Router\\RouterInterface'];
        $request = new CurrentRequest();
        $route = $router->route($request);
        if ($route) {
            $route->execute();
        } else {
            throw new RouteNotFoundException();
        }
    }

    protected function loadPackage()
    {
        $this->container['Packfire\\Framework\\Package\\LoaderInterface']->load($this->bootPath);

        if (is_dir($this->bootPath . '/vendor')) {
            foreach (glob($this->bootPath . '/vendor/*', GLOB_ONLYDIR) as $folder) {
                $this->container['Packfire\\Framework\\Package\\LoaderInterface']->load($folder);
            }
        }
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
