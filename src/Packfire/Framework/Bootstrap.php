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
use Packfire\FuelBlade\ServiceLoader as FuelBladeLoader;

class Bootstrap
{
    /**
     * The FuelBlade IoC Container used in booting
     * @var Packfire\FuelBlade\ContainerInterface
     */
    protected $container;

    /**
     * The pathname of the folder Bootstrap was called from
     * @var string
     */
    protected $bootPath;

    /**
     * Create a new Bootstrap object
     * @param string $bootPath (optional) The path to boot from
     * @param Packfire\FuelBlade\ContainerInterface $container (optional) The container to inject dependencies into Bootstrap
     * @return void
     */
    public function __construct($bootPath = null, ContainerInterface $container = null)
    {
        $this->container = $container ? $container : $this->createContainer();

        $this->bootPath = $bootPath;
        if (!$this->bootPath) {
            $backtrace = debug_backtrace();
            $this->bootPath = pathinfo($backtrace[0]['file'], PATHINFO_DIRNAME);
        }
    }

    /**
     * Create and prepare a new IoC container
     * @return Packfire\FuelBlade\ContainerInterface Returns a newly created IoC Container
     */
    protected function createContainer()
    {
        $container = new Container();
        $container['Packfire\\FuelBlade\\ContainerInterface'] = $container;
        return $container;
    }

    /**
     * Bootstrap the framework
     * @return void
     */
    public function run()
    {
        $services = new ServiceLoader($this->container);
        $services->load();

        $whoops = $this->container['Whoops\\Run'];
        $whoops->pushHandler($this->container['Whoops\\Handler\\HandlerInterface']);
        $whoops->register();

        $this->loadPackage();

        if (!isset($this->container['Packfire\\Router\\RouterInterface'])) {
            $configManager = $this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'];
            $this->container['Packfire\\Router\\RouterInterface'] = $this->container->instantiate('Packfire\\Router\\ConfigLoader', array('config' => $configManager->get('routes')))->load();
        }

        $this->routeRequest();
    }

    /**
     * Load the packages from the folders
     * @return void
     */
    protected function loadPackage()
    {
        $loader = $this->container['Packfire\\Framework\\Package\\LoaderInterface'];
        $loader->load($this->bootPath);

        if (is_dir($this->bootPath . '/vendor')) {
            foreach (glob($this->bootPath . '/vendor/*/*', GLOB_ONLYDIR) as $folder) {
                $loader->load($folder);
            }
        }

        $configManager = $this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'];
        if (isset($configManager['services'])) {
            $services = $configManager['services']->get('services');
            FuelBladeLoader::load($this->container, $services);
        }
    }

    /**
     * Perform the routing of the current request
     * @return void
     */
    protected function routeRequest()
    {
        $router = $this->container['Packfire\\Router\\RouterInterface'];
        $request = new CurrentRequest();
        $route = $router->route($request);
        if ($route) {
            $route->execute();
        } else {
            throw new RouteNotFoundException();
        }
    }

    /**
     * Get the pathname of the folder where boot started
     * @return string Returns the pathname
     */
    public function bootPath()
    {
        return $this->bootPath;
    }

    /**
     * Get the FuelBlade IoC Container of Bootstrap
     * @return Packfire\FuelBlade\ContainerInterface Returns the container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
