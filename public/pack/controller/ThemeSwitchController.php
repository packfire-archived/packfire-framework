<?php
pload('app.AppController');
pload('packfire.controller.pValidationFilter');
pload('packfire.validator.pMatchValidator');

/**
 * Switches between themes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package candice.controller
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