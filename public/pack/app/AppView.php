<?php
pload('packfire.view.pView');
pload('AppTemplate');

/**
 * The generic application view class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.app
 * @since 1.0-sofia
 */
abstract class AppView extends pView {
    
    protected function template($template) {
        if(is_string($template)){
            $template = AppTemplate::load('home');
        }
        return parent::template($template);
    }

    protected function theme($theme) {
        return parent::theme($theme);
    }

    
}