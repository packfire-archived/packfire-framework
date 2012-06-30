<?php
pload('app.AppController');
pload('packfire.controller.pValidationFilter');
pload('packfire.validator.pMatchValidator');

/**
 * ThemeSwitchController class
 * 
 * Switches between themes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.welcome
 * @since 1.0-sofia
 */
class ThemeSwitchController extends AppController {
    
    public function doIndex(){
        $this->filter('theme', array(
                'trim',
                new pValidationFilter(new pMatchValidator(array('dark', 'light'))
            )));
        $this->service('session')->set('theme', $this->params['theme']);
        $this->redirect($this->route('home'));
    }
    
}