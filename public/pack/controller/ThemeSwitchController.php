<?php
pload('app.AppController');

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
        $this->bucket->pick('session')->set('theme', $this->params['theme']);
        $this->redirect('/');
    }
    
}