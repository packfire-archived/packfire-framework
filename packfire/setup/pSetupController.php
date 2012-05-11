<?php
pload('packfire.controller.pController');
pload('packfire.generator.pAppGenerator');
pload('packfire.io.file.pPath');
pload('packfire.setup.view.*');

/**
 * Packfire's Setup Controller
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.setup
 * @since 1.0-sofia
 */
class pSetupController extends pController {
    
    public function doInstallFramework(){
        $root = $this->params->get('root');
        if($root && __PACKFIRE_ROOT__ != $root){
            pPath::copy(__PACKFIRE_ROOT__, $root);
        }
        $this->state['complete'] = true;
        $this->state['root'] = $root;
    }
    
    public function getInstallFramework(){
        
    }
    
    public function doCreateApplication(){
        $root = $this->params->get('root');
        $packfire = $this->params->get('packfire');
        if($root && $packfire){
            $generator = new pAppGenerator($root, $packfire);
            $generator->generate();
            $this->state['complete'] = true;
            $this->state['root'] = $root;
            $this->state['packfire'] = $packfire;
        }else{
            $this->state['fail'] = true;
        }
    }
    
    public function getCreateApplication(){
        
    }
    
    public function doWelcome(){
    }
    
}