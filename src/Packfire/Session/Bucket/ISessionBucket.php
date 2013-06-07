<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session\Bucket;

/**
 * Session Bucket Interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Session\Bucket
 * @since 1.0-sofia
 */
interface ISessionBucket
{
    public function id();

    public function load(&$data = null);

    public function has($name);

    public function get($name, $default = null);

    public function set($name, $value);

    public function remove($name);

    public function clear();
}
