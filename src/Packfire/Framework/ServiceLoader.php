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

class ServiceLoader
{
    /**
     * The FuelBlade IoC Container to be loaded
     * @var string
     */
    protected $container;

    protected $defaults = array();

    /**
     * Create a new ServiceLoader object
     * @param Packfire\FuelBlade\ContainerInterface $container The container to be loaded with default dependencies
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->defaults = array(
            'Packfire\\Framework\\Package\\ConfigManagerInterface' => 'Packfire\\Framework\\Package\\ConfigManager',
            'Packfire\\Framework\\Package\\LoaderInterface' => 'Packfire\\Framework\\Package\\Loader',
            'Psr\\Log\\LoggerInterface' => function ($container) {
                return $container->instantiate('Packfire\\Logger\\File', array('file' => 'packfire.log'));
            },
            'Packfire\\Session\\StorageInterface' => 'Packfire\\Session\\Storage',
            'Packfire\\Session\\SessionInterface' => 'Packfire\\Session\\Session',
            'Whoops\\Run' => 'Whoops\\Run',
            'Whoops\\Handler\\HandlerInterface' => 'Whoops\\Handler\\PrettyPageHandler',
        );
    }

    /**
     * Bootstrap the framework
     * @return void
     */
    public function load()
    {
        foreach ($this->defaults as $interface => $concrete) {
            if (!isset($this->container[$interface])) {
                $this->container[$interface] = is_callable($concrete)
                    ? $concrete
                    : $this->container->share($this->container->instance($concrete));
            }
        }
    }

    /**
     * Get the FuelBlade IoC Container of ServiceLoader
     * @return Packfire\FuelBlade\ContainerInterface Returns the container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
