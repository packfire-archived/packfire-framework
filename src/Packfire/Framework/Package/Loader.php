<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework\Package;

use Symfony\Component\Finder\Finder;
use Packfire\FuelBlade\ContainerInterface;
use Packfire\FuelBlade\ConsumerInterface;
use Packfire\Config\ConfigFactory;
use Packfire\Framework\Exceptions\ConfigLoadFailException;

class Loader implements LoaderInterface, ConsumerInterface
{
    /**
     * The IoC container of Loader
     * @var Packfire\FuelBlade\ContainerInterface
     */
    protected $container;

    /**
     * Create a new Loader object
     * @param ContainerInterface $container The IoC container for injecting dependencies into Loader
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Load configuration files from package path
     * @param  string $path The path to the package to load from
     * @return void
     */
    public function load($path)
    {
        if (is_dir($path . '/config')) {
            $configManager = $this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'];

            $finder = new Finder();
            $finder->files()->in($path . '/config');
            $factory = new ConfigFactory();
            foreach ($finder as $file) {
                $config = $factory->load($file->getPathname());
                if ($config) {
                    $configManager->commit($file->getBasename('.' . $file->getExtension()), $config);
                } else {
                    throw new ConfigLoadFailException($file->getPathname());
                }
            }
        }
    }

    /**
     * Inject the object with the IoC container
     * @param  Packfire\FuelBlade\ContainerInterface|array $container The FuelBlade IoC Container
     * @return Loader Returns self
     */
    public function __invoke($container)
    {
        $this->container = $container;
        return $this;
    }
}
