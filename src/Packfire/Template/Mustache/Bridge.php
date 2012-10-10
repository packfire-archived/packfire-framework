<?php
namespace Packfire\Template\Mustache;

use Packfire\Template\Mustache\Mustache;
use Packfire\Application\Pack\Template;
use Packfire\Collection\ArrayList;

/**
 * Bridge class
 * 
 * Mustache bridge that allows loading of partials from Application Templates
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template\Mustache
 * @since 1.1-sofia
 */
class Bridge extends Mustache {
    
    /**
     * Get the partial by name and add to the buffer
     * @param string $name Name of the partial
     * @since 1.0-sofia
     */
    protected function partial($name){
        /* @var $template ITemplate */
        $template = Template::load($name);
        if($template){
            $template->set($this->parameters);
            $this->buffer .= $template->parse();
        }
    }
    
    protected function loadParameters(){
        if($this->parameters instanceof ArrayList){
            $this->parameters = $this->parameters->toArray();
        }
    }
    
}