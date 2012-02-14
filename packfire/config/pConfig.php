<?php
pload('packfire.yaml.pYaml');

/**
 * pConfig Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pConfig {
   
    private $context;
    
    private $data;
    
    public function __construct($name, $context = null){
        $configFile = __APP_ROOT__ . 'packfire/config/' . $name . ($context ? ('.' . $context) : '') . '.yml';
        
    }
    
    
}