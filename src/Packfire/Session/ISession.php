<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session;

/**
 * Session abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session
 * @since 1.0-sofia
 */
interface ISession
{
    public function __construct($storage);

    public function get($key);

    public function set($key, $value);

    public function clear();

    public function invalidate();

    public function regenerate();

}
