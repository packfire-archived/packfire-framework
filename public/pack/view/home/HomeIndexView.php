<?php
pload('app.AppView');

/**
 * HomeView
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package candice.view.home
 * @since 1.0-sofia
 */
class HomeIndexView extends AppView {
    
    protected function create() {
        $theme = $this->service('session')->get('theme', 'dark');
        if(!in_array($theme, array('dark', 'light'))){
            $theme = 'light';
        }
        $this->theme($theme);
        
        $rootUrl = $this->route('home');
        $this->define('rootUrl', $rootUrl . '/');
        $this->define('title', $this->state['title']);
        $this->define('message', $this->state['message']);
        $this->define('version', $this->service('config.app')->get('app', 'version'));
        
        $this->define('themeDark', $this->route('themeSwitch', array('theme' => 'dark')));
        $this->define('themeLight', $this->route('themeSwitch', array('theme' => 'light')));
    }

}