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

use Packfire\Collection\ArrayList;

/**
 * Provides funtionalities to work with the file system
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO\File
 * @since 1.0-sofia
 */
class System
{
    /**
     * Check whether a file exists in the file system
     * @param  string  $file Path name to the file
     * @return boolean Returns true if the file exists, false otherwise.
     * @since 1.0-sofia
     */
    public static function fileExists($file)
    {
        return (bool) is_file($file);
    }

    /**
     * Check whether a path directory exists in the file system
     * @param  string  $path Path name to the directory
     * @return boolean Returns true if the directory exists, false otherwise.
     * @since 1.0-sofia
     */
    public static function pathExists($path)
    {
        return (bool) is_dir($path);
    }

    /**
     * Get the available disk space of a specific directory
     * @param  string $dir The directory path
     * @return double Returns the amount of disk free space in bytes
     * @link http://php.net/disk-free-space
     * @since 1.0-sofia
     */
    public static function freeSpace($dir)
    {
        return disk_free_space($dir);
    }

    /**
     * Get the total disk space of a specific directory
     * @param  string $dir The directory path
     * @return double Returns the amount of disk free space in bytes
     * @link http://php.net/disk-total-space
     * @since 1.0-sofia
     */
    public static function totalSpace($dir)
    {
        return disk_total_space($dir);
    }

    /**
     * Search for files given a particular pattern
     * @param  string    $pattern Pattern to use for search. See link for more info.
     * @param  integer   $flags   (optional) Flags to use for the pattern search.
     * @return ArrayList Returns the collection of files matching the pattern.
     * @link http://php.net/glob
     * @since 1.0-sofia
     */
    public static function pathSearch($pattern, $flags = 0)
    {
        $result = glob($pattern, $flags);

        return new ArrayList($result);
    }
}
