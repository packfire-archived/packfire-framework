<?php
namespace Packfire\Core\ClassLoader;

use Packfire\Collection\Iterator;
use Packfire\IO\File\Path;

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
            if($file->isFile() && $file->getExtension() == 'php'){
                $tokens = token_get_all(file_get_contents((string)$file));
                $namespace = '';
                $classes = array();
                $iterator = new Iterator($tokens);
                while($iterator->more()){
                    $token = $iterator->current();
                    if (!is_string($token)) {
                        list($id, $text) = $token;
                        if ($id == T_NAMESPACE) {
                            $iterator->next();
                            list($id, $text) = $iterator->next();
                            $namespace = $text;
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