<?php

class pFileSystem {

    /**
     * Check whether a file exists in the file system
     * @param string $f Path name to the file
     * @return boolean TRUE if the file exists, FALSE otherwise.
     * @static
     */
    public static function fileExists($f){
        return (bool)is_file($f);
    }

    /**
     * Check whether a path directory exists in the file system
     * @param string $f Path name to the directory
     * @return boolean TRUE if the directory exists, FALSE otherwise.
     * @static
     */
    public static function pathExists($p){
        return (bool)is_dir($p);
    }

    /**
     * Get the available disk space of a specific directory
     * @param string $d The directory path
     * @return double
     * @link http://php.net/disk-free-space
     * @static
     */
    public static function freeSpace($d){
        return disk_free_space($d);
    }

    /**
     * Get the total disk space of a specific directory
     * @param string $d The directory path
     * @return double
     * @link http://php.net/disk-total-space
     * @static
     */
    public static function totalSpace($d){
        return disk_total_space($d);
    }

    /**
     * Search for files given a particular pattern
     * @param string $pattern Pattern to use for search. See link for more info.
     * @param integer $flags (optional) Flags to use for the pattern search. See link for more info.
     * @return RaiseCollection The collection of files matching the pattern.
     * @link http://php.net/glob
     * @static
     */
    public static function pathSearch($pattern, $flags = 0){
        $a = glob($pattern, $flags);
        return new pList($a);
    }
    
}
