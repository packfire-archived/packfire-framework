<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework\Package;

use Packfire\Config\ConfigInterface;
use Packfire\Config\Config;

class ConfigManager implements ConfigManagerInterface
{
    protected $configs = array();

    public function commit($name, ConfigInterface $config)
    {
        if (!isset($this->configs[$name])) {
            $this->configs[$name] = new Config;
        }
        $this->configs[$name]->merge($config);
    }

    public function get($name)
    {
        return $this->configs[$name];
    }

    public function remove($name)
    {
        unset($this->configs[$name]);
    }

    public function offsetExists($name)
    {
        return isset($this->configs[$name]);
    }

    public function offsetGet($name)
    {
        return $this->get($name);
    }

    public function offsetSet($name, $config)
    {
        $this->commit($name, $config);
    }

    public function offsetUnset($name)
    {
        unset($this->configs[$name]);
    }

    public function &__get($name)
    {
        return $this->configs[$name];
    }

    public function __set($name, $config)
    {
        $this->commit($name, $config);
    }
}
