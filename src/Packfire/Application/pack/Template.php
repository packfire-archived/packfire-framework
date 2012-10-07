<?php
namespace Packfire\Appliation\Pack;

/**
 * pAppTemplate class
 * 
 * Performs template loading for application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Appliation\Pack
 * @since 1.1-sofia
 */
class Template {
    
    /**
     * Load a template from the template folder
     * @param string $name Name of the template to load
     * @return ITemplate Returns the template
     * @since 1.0-sofia
     */
    public static function load($name){
        $path = __APP_ROOT__ . 'pack/template/' . $name;
        
        // parsers
        $extensions = array(
            'html' => 'packfire.template.moustache.pMoustacheFile',
            'htm' => 'packfire.template.moustache.pMoustacheFile',
            'php' => 'packfire.template.pPhpTemplateFile'
        );
        
        $template = null;
        foreach($extensions as $type => $package){
            if(is_file($path . '.' .  $type)){
                pload($package);
                list(, $class) = pClassLoader::resolvePackageClass($package);
                $template = new $class($path . '.' .  $type);
            }
        }
        
        return $template;
    }
    
}