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
 * Constants to parts of a path
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO\File
 * @since 1.0-sofia
 */
class PathPart {

    /**
     * Get the directory name
     */
    const DIRECTORY = 'dirname';

    /**
     * Get the filename
     */
    const BASENAME = 'basename';

    /**
     * Get the file extension
     */
    const EXTENSION = 'extension';

    /**
     * Get the file name without the file extension
     */
    const FILENAME = 'filename';
    
}
