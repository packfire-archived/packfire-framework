<?php
pload('packfire.view.pView');
pload('AppTemplate');
pload('AppTheme');

/**
 * The generic application view class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.app
 * @since 1.0-sofia
 */
abstract class AppView extends pView {
    
    public function __construct(){
        parent::__construct();
        
        // set the default template as the name of the view class
        $template = get_class($this);
        if(strtolower(substr($template, -4)) === 'view'){
            $template = substr($template, 0, strlen($template) - 4);
        }
        $this->template($template);
    }
    
    protected function template($template) {
        if(is_string($template)){
            $template = AppTemplate::load($template);
        }
        return parent::template($template);
    }

    protected function theme($theme) {
        if(is_string($theme)){
            $theme = AppTheme::load($theme);
        }
        return parent::theme($theme);
    }
    
}