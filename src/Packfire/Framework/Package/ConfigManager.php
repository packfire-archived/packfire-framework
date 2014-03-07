<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework\Package;

use Packfire\Config\ConfigInterface;
use Packfire\Config\Config;
use Packfire\Framework\Exceptions\ConfigNotFoundException;

class ConfigManager implements ConfigManagerInterface
{
    /**
     * All the configurations managed by the manager
     * @var array
     */
    protected $configs = array();

    /**
     * Commit a configuration into the manager. If the configuration has already been added before, the old and new will merge.
     * @param  string          $name   The name of the configuration
     * @param  ConfigInterface $config The configuration to commit into the manager.
     * @return void
     */
    public function commit($name, ConfigInterface $config)
    {
        if (!isset($this->configs[$name])) {
            $this->configs[$name] = new Config();
        }
        $this->configs[$name]->merge($config);
    }

    /**
     * Get a configuration for use
     * @param  string $name Name of the configuration to fetch.
     * @return ConfigInterface Returns the configuration from the manager
     * @throws ConfigNotFoundException Thrown when the configuration could not be found in the manager
     */
    public function get($name)
    {
        if (!isset($this->configs[$name])) {
            throw new ConfigNotFoundException($name);
        }
        return $this->configs[$name];
    }

    /**
     * Remove a configuration from the manager
     * @param  string $name The name of the configuration to remove.
     * @return void
     */
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
