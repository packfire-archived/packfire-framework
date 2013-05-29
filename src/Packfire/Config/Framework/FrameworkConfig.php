<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Config\Framework;

use Packfire\Config\ConfigType;
use Packfire\Config\ConfigFactory;
use Packfire\Config\Framework\IFrameworkConfig;

/**
 * Application configuration parser
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Framework
 * @since 1.0-sofia
 */
abstract class FrameworkConfig implements IFrameworkConfig
{
    /**
     * Load an application configuration file located the the config folder.
     * @param string $name    Name of the configuration file to load
     * @param string $context (optional) The context from which we are loading
     *                        the configuration file. $context can be any string
     *                        value such as 'local', 'test' or 'live' to determine
     *                        what values are loaded.
     * @return Config Returns a Config that has read and parsed the configuration file,
     *                 or NULL if the file is not recognized or not found.
     * @since 1.0-sofia
     */
    protected function loadConfig($name, $context)
    {
        $path = __APP_ROOT__ . '/config/' . $name;

        $map = array_keys(ConfigType::typeMap());

        $factory = new ConfigFactory();
        $default = null;
        foreach ($map as $type) {
            if (is_file($path . '.' . $type)) {
                $default = $factory->load($path . '.' . $type);
            }
            if ($context && is_file($path . '.' . $context . '.' . $type)) {
                return $factory->load($path . '.' . $context . '.' . $type, $default);
            } elseif ($default) {
                return $default;
            }
        }

        return null;
    }

}
