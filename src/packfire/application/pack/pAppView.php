<?php
pload('packfire.view.pView');
pload('pAppTemplate');
pload('pAppTheme');

/**
 * pAppView class
 * 
 * The generic application view class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.pack
 * @since 1.1-sofia
 */
abstract class pAppView extends pView {
    
    /**
     * Create a new pAppView object
     * @since 1.1-sofia 
     */
    public function __construct(){
        parent::__construct();
        
        // set the default template as the name of the view class
        $template = get_class($this);
        if(strtolower(substr($template, -4)) === 'view'){
            $template = substr($template, 0, strlen($template) - 4);
        }
        $this->template($template);
    }
    
    /**
     * Set the template for the view class
     * @param ITemplate|string $template The template or name of the template
     *          to set for the view class.
     * @return AppView Returns the object for chaining
     * @since 1.1-sofia
     */
    protected function template($template) {
        if(is_string($template)){
            $template = pAppTemplate::load($template);
        }
        return parent::template($template);
    }

    /**
     * Set the theme for the view class
     * @param pTheme|string $theme The theme or the name of the theme class to
     *          set to the view class
     * @return AppView Returns the object for chaining
     * @since 1.1-sofia
     */
    protected function theme($theme) {
        if(is_string($theme)){
            $theme = pAppTheme::load($theme);
        }
        return parent::theme($theme);
    }
    
}