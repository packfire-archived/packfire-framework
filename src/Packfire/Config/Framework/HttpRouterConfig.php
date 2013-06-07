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

use Packfire\Config\Framework\FrameworkConfig;

/**
 * Router configuration parser for HTTP routes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Framework
 * @since 1.0-sofia
 */
class HttpRouterConfig extends FrameworkConfig
{
    /**
     * Load the routing configuration file located the the config folder.
     * @param string $context (optional) The context from which we are loading
     *                        the configuration file. $context can be any string
     *                        value such as 'local', 'test' or 'live' to determine
     *                        what values are loaded.
     * @return Config Returns a Config that has read and parsed the configuration file,
     *                 or NULL if the file is not recognized or not found.
     * @since 1.0-sofia
     */
    public function load($context = __ENVIRONMENT__)
    {
        return $this->loadConfig('routing', $context);
    }
}
