<?php
pload('pMoustache');
pload('packfire.application.pack.pAppTemplate');

/**
 * pMoustacheBridge class
 * 
 * Moustache bridge that allows loading of partials from Application Templates
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.template.moustache
 * @since 1.1-sofia
 */
class pMoustacheBridge extends pMoustache {
    
    /**
     * Get the partial by name and add to the buffer
     * @param string $name Name of the partial
     * @since 1.0-sofia
     */
    protected function partial($name){
        /* @var $template ITemplate */
        $template = pAppTemplate::load($name);
        if($template){
            $template->set($this->parameters);
            $this->buffer .= $template->parse();
        }
    }
    
    protected function loadParameters(){
        if($this->parameters instanceof pList){
            $this->parameters = $this->parameters->toArray();
        }
    }
    
}