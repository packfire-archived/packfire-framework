<?php
pload('packfire.template.pHtmlTemplate');

/**
 * AppTemplate Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.app
 * @since 1.0-sofia
 */
class AppTemplate {
    
    /**
     * 
     * @param type $name
     * @param type $context
     * @return \class 
     */
    public static function load($name, $context = __ENVIRONMENT__){
        $path = __APP_ROOT__ . 'pack/template/' . $name;
        // parsers
        $extensions = array(
            'html' => 'pTemplate',
            'htm' => 'pTemplate',
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