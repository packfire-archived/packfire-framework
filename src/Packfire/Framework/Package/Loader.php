<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework\Package;

use Symfony\Component\Finder\Finder;
use Packfire\FuelBlade\ConsumerInterface;
use Packfire\Config\ConfigFactory;
use Packfire\Framework\Exceptions\ConfigLoadFailException;

class Loader implements LoaderInterface, ConsumerInterface
{
    protected $configManager;

    /**
     * Create a new Loader object
     * @param ConfigManagerInterface $configManager The configuration manager to load into
     * @return void
     */
    public function __construct(ConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * Load configuration files from package path
     * @param  string $path The path to the package to load from
     * @return void
     */
    public function load($path)
    {
        if (is_dir($path . '/config')) {
            $finder = new Finder();
            $finder->files()->in($path . '/config');
            $factory = new ConfigFactory();
            foreach ($finder as $file) {
                $config = $factory->load($file);
                if ($config) {
                    $this->configManager->commit(basename($file), $config);
                } else {
                    throw new ConfigLoadFailException($file);
                }
            }
        }
    }

    /**
     * Get the configuration manager in this loader
     * @return ConfigManagerInterface Returns the configuration manager
     */
    public function config()
    {
        return $this->configManager;
    }

    /**
     * Inject the object with the IoC container
     * @param  Packfire\FuelBlade\ContainerInterface|array $container The FuelBlade IoC Container
     * @return Loader Returns self
     */
    public function __invoke($container)
    {
        return $this;
    }
}
