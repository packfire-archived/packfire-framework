<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework\Package;

use Symfony\Component\Finder\Finder;
use Packfire\FuelBlade\ConsumerInterface;

class Loader implements LoaderInterface, ConsumerInterface
{
    protected $configManager;

    public function __construct(ConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;
    }

    public function load($path)
    {
        if (is_dir($path . '/config')) {
            $finder = new Finder();
            $finder->files()->in($path . '/config');
            foreach ($finder as $file) {

            }
        }
    }

    public function config()
    {
        return $this->configManager;
    }

    public function __invoke($container)
    {
        return $this;
    }
}
