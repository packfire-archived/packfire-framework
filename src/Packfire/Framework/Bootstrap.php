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

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ? $container : new Container();
    }

    public function run()
    {
        $this->container['Packfire\\Framework\\Package\\ConfigManagerInterface'] = $this->container->instantiate('Packfire\\Framework\\Package\\ConfigManager');
        $this->container['Packfire\\Router\\RouterInterface'] = $this->container->instantiate('Packfire\\Router\\Router');
        $this->container['Packfire\\Framework\\Package\\LoaderInterface'] = $this->container->instantiate('Packfire\\Framework\\Package\\Loader');
    }

    public function getContainer()
    {
        return $this->container;
    }
}
