<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\View;

use Packfire\Collection\Map;

/**
 * A theme abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\View
 * @since 1.0-sofia
 */
abstract class Theme
{
    /**
     * The fields in the theme settings defined
     * @var Map
     * @since 1.0-sofia
     */
    private $fields;

    /**
     * Create a new Theme object
     * @since 1.0-sofia
     */
    public function __construct()
    {
        $this->fields = new Map();
    }

    /**
     * Get the theme settings defined
     * @return Map Returns a map of theme settings
     * @since 1.0-sofia
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Define a theme setting
     * @param string|array|Map $key   The name of the theme setting.
     * @param mixed            $value (optional) The value of the theme setting.
     * @since 1.0-sofia
     */
    protected function define($key, $value = null)
    {
        if (func_num_args() == 1) {
            $this->fields->append($key);
        } else {
            $this->fields[$key] = $value;
        }
    }

    /**
     * Prepare and render the theme settings
     * @since 1.0-sofia
     */
    abstract public function render();
}
