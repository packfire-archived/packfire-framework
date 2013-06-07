<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session\Storage;

use Packfire\Session\Storage\SessionStorage;

/**
 * File storage for session
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Session\Storage
 * @since 1.0-sofia
 */
class FileSessionStorage extends SessionStorage
{
    /**
     * Path to the session storage location on the file system
     * @var string
     * @since 1.0-sofia
     */
    private $path;

    /**
     * Create a new FileSessionStorage object
     * @param string $path Path to the storage location
     * @since 1.0-sofia
     */
    public function __construct($path = null)
    {
        if ($path) {
            $this->path = $path;
        } else {
            $this->path = '';
        }
    }

    /**
     * Register the handler
     * @internal
     * @since 1.0-sofia
     */
    protected function registerHandler()
    {
        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', $this->path);
    }
}
