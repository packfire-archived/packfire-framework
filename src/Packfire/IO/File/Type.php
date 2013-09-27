<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\IO\File;

/**
 * File type constants
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO\File
 * @since 1.0-sofia
 */
class Type
{
    /**
     * Directory
     */
    const DIR = 'dir';

    /**
     * File
     */
    const FILE = 'file';

    /**
     * Socket
     */
    const SOCKET = 'socket';

    /**
     * Link
     */
    const LINK = 'link';

    /**
     * Others
     */
    const OTHERS = 'others';
}
