<?php
namespace Packfire\Core\ClassLoader;

use Packfire\Collection\Iterator;
use Packfire\IO\File\Path;

/**
 * ClassMapBuilder class
 * 
 * Helps to build an array of classes with namespaces and paths
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
class ClassMapBuilder {
    
    /**
     * Build the class map
     * @param string $path The path to the folder to start building the class map.
     * @return array Returns the array of class map. Class's paths returned are relative to the $path provided.
     * @since 2.0.0
     */
    public function build($path){
        $map = array();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($iterator as $file){
            // for PHP prior to 5.3.6
            // see http://php.net/manual/en/splfileinfo.getextension.php
            $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            if($file->isFile() && $extension == 'php'){
                $tokens = token_get_all(file_get_contents((string)$file));
                $namespace = '';
                $classes = array();
                $iterator = new Iterator($tokens);
                while($iterator->more()){
                    $token = $iterator->current();
                    if (!is_string($token)) {
                        list($id, $text) = $token;
                        if ($id == T_NAMESPACE) {
                            $ns = '';
                            $iterator->next();
                            while($iterator->more()){
                                $token = $iterator->next();
                                if($token == ';'){
                                    break;
                                }elseif(!is_string($token)){
                                    list($id, $text) = $token;
                                    $ns .= $text;
                                }
                            }
                            $namespace = $ns;
                        }
                        if ($id == T_CLASS) {
                            $iterator->next();
                            list($id, $text) = $iterator->next();
                            $classes[$namespace . '\\' . $text] = Path::relativePath($path, (string)$file);
                        }
                    }
                    
                    $iterator->next();
                }
                $map = array_merge($map, $classes);
            }
        }
        return $map;
    }
    
}