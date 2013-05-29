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

use Packfire\IO\File\IFile;
use Packfire\IO\File\System as FileSystem;
use Packfire\IO\File\Stream as FileStream;
use Packfire\DateTime\DateTime;
use Packfire\Exception\IOException;

/**
 * File operations provider
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO\File
 * @since 1.0-sofia
 */
class File implements IFile
{
    /**
     * Actual resolved pathname of the file
     * @var string
     * @since 1.0-sofia
     */
    protected $pathname;

    /**
     * Create a new File object
     * @param string $file Pathname of the file
     * @since 1.0-sofia
     */
    public function __construct($file)
    {
        $this->pathname = $file;
    }

    /**
     * Get the file size
     * @return integer Returns the file size or NULL if the file is not found.
     * @since 1.0-sofia
     */
    public function size()
    {
        if ($this->exists()) {
            return filesize($this->pathname);
        } else {
            return null;
        }
    }

    /**
     * Create the file if file does not exists.
     * @since 1.0-sofia
     */
    public function create()
    {
        if (!$this->exists()) {
            $handle = @fopen($this->pathname, 'w');
            @fclose($handle);
        }
    }

    /**
     * Tell if the file exists or not
     * @return boolean Returns true if the file exists, false otherwise.
     * @since 1.0-sofia
     */
    public function exists()
    {
        return is_file($this->pathname);
    }

    /**
     * Delete the file
     * @throws IOException
     * @since 1.0-sofia
     */
    public function delete()
    {
        if (!@unlink($this->pathname)) {
            throw new IOException(
                    sprintf('An error occurred deleting file \'%s\'.',
                            $this->pathname)
                );
        }
    }

    /**
     * Set the file content
     * @param  string      $content The content of the file
     * @throws IOException
     * @since 1.0-sofia
     */
    public function write($content)
    {
        if (!@file_put_contents($this->pathname, $content)) {
            throw new IOException(
                    sprintf('Failed to write content file \'%s\'.', $this->pathname)
                );
        }
    }

    /**
     * Append the file content
     * @param  string      $content The additional file content to append
     * @return bool        Returns true if successful, false otherwise.
     * @throws IOException
     * @since 1.0-sofia
     */
    public function append($content)
    {
        $link = @fopen($this->pathname, 'a');
        if ($link) {
            if (!@fwrite($link, $content)) {
                throw new IOException(
                    sprintf('An error occurred while appending '.
                            'content to file \'%s\'.', $this->pathname)
                );
            }
            @fclose($link);
        } else {
            throw new IOException(
                sprintf('Failed opening file \'%s\'.', $this->pathname)
            );
        }
    }

    /**
     * Get the entire file content
     * @return string      The entire file content is returned if successful.
     * @throws IOException
     * @since 1.0-sofia
     */
    public function read()
    {
        $content = @file_get_contents($this->pathname);
        if ($content === false) {
            throw new IOException(
                    sprintf('An error occurred reading file \'%s\'.',
                            $this->pathname)
                );
        }

        return $content;
    }

    /**
     * Copy the file to another destination
     * @param  string $destination The destination path to copy to
     * @return File   Returns the file object that maps to the new copy at the
     *               destination path.
     * @throws IOException
     * @since 1.0-sofia
     */
    public function copy($destination)
    {
        if (FileSystem::pathExists($destination)) {
            $destination = Path::combine($destination,
                    Path::baseName($this->pathname));
        }
        if (@copy($this->pathname, $destination)) {
            return new self($destination);
        } else {
            throw new IOException(
                    sprintf('Failed to copy file \'%s\' to destination \'%s\'.',
                            $this->pathname, $destination)
                );
        }
    }

    /**
     * Get the entire pathname to the file
     * @return string Returns the string to the entire pathname
     * @since 1.0-sofia
     */
    public function pathname()
    {
        return $this->pathname;
    }

    /**
     * Rename the file
     * @param  string      $newname The new name to give to the file
     * @throws IOException
     * @since 1.0-sofia
     */
    public function rename($newname)
    {
        $newname = Path::path($this->pathname) . DIRECTORY_SEPARATOR
                . Path::baseName($newname);
        if (@rename($this->pathname, $newname)) {
            $this->pathname = $newname;
        } else {
            throw new IOException(
                    sprintf('An error occurred renaming file \'%s\' to \'%s\'.',
                            $this->pathname, $newname)
                );
        }
    }

    /**
     * Move the file to another directory path
     * @param  string      $newdir The new directory path to move the file to
     * @throws IOException
     * @since 1.0-sofia
     */
    public function move($newdir)
    {
        $newdir = $newdir . DIRECTORY_SEPARATOR
                . Path::baseName($this->pathname);
        if (@rename($this->pathname, $newdir)) {
            $this->pathname = $newdir;
        } else {
            throw new IOException(
                    sprintf('An error occurred moving file \'%s\' to \'%s\'.',
                            $this->pathname, $newdir)
                );
        }
    }

    /**
     * Get or set the Last Modified attribute of the file
     * @param  DateTime $datetime (optional) The datetime to set to
     * @return DateTime The last modified timestamp of the file.
     * @since 1.0-sofia
     */
    public function lastModified($datetime = null)
    {
        if (func_num_args() == 1) {
            if (!@touch($this->pathname, $datetime->toTimestamp())) {
                throw new IOException('Failed to set last modified time for'
                        . ' file "'. $this->pathname . '".');
            }

            return $datetime;
        } else {
            if ($time = @filemtime($this->pathname)) {
                return DateTime::fromTimestamp($time);
            } else {
                throw new IOException('Failed to retrieve last modified time'
                        . ' for file "'. $this->pathname . '".');
            }
        }
    }

    /**
     * Get the permission of the file
     * @param  integer $permission (optional) The permission to set to.
     * @return integer Returns the permission of the file
     * @link http://php.net/chmod
     * @since 1.0-sofia
     */
    public function permission($permission = null)
    {
        if (func_num_args() == 1) {
            if (!@chmod($this->pathname, $permission)) {
                throw new IOException('Failed to perform file permission'
                        . ' change for file "' . $this->pathname . '".');
            }

            return $permission;
        } else {
            if ($perm = @fileperms($this->pathname)) {
                return substr(decoct($perm), 2);
            } else {
                throw new IOException('Failed to retrieve file permission'
                        . ' for file "' . $this->pathname . '".');
            }
        }
    }

    /**
     * Get the stream for this file
     * @return FileStream Returns the stream to access this file
     * @since 1.0-sofia
     */
    public function stream()
    {
        return new FileStream($this->pathname);
    }

    /**
     * Get the pathname of the file
     * @return string Returns the pathname to the file
     * @since 2.0.0
     */
    public function __toString()
    {
        return $this->pathname;
    }

}
