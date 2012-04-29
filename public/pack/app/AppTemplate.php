<?php
pload('packfire.template.pTemplate');
pload('packfire.template.moustache.pMoustacheTemplate');


/**
 * Application Template Loading Buddy
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.app
 * @since 1.0-sofia
 */
class AppTemplate {
    
    /**
     * Load a template from the template folder
     * @param string $name Name of the template to load
     * @param string $context The environmental context
     * @return ITemplate Returns the template
     * @since 1.0-sofia
     */
    public static function load($name, $context = __ENVIRONMENT__){
        $path = __APP_ROOT__ . 'pack/template/' . $name;
        
        // parsers
        $extensions = array(
            'html' => 'pMoustacheTemplate',
            'htm' => 'pMoustacheTemplate',
        );
        
        $template = null;
        // fall back if with context the file is not found
        foreach($extensions as $type => $class){
            if(is_file($path . '.' .  $type)){
                $fileContent = file_get_contents($path . '.' .  $type);
                $template = new $class($fileContent);
            }
        }
        
        return $template;
    }
    
}