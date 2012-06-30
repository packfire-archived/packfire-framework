<?php
pload('packfire.view.pView');
pload('packfire.template.moustache.pMoustacheTemplate');
pload('LightTheme');
pload('DarkTheme');

/**
 * HomeIndexView class
 * 
 * View for the homepage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.welcome
 * @since 1.0-sofia
 */
class HomeIndexView extends pView {
    
    protected function create() {
        $theme = $this->service('session')->get('theme', 'dark');
        if(!in_array($theme, array('dark', 'light'))){
            $theme = 'light';
        }
        $template = new pMoustacheTemplate(file_get_contents(dirname(__FILE__) . '/HomeIndex.html'));
        $this->theme($theme == 'dark' ? new DarkTheme() : new LightTheme())
            ->template($template);
        
        $rootUrl = $this->route('home');
        $this->define('rootUrl', $rootUrl);
        $this->define('title', $this->state['title']);
        $this->define('message', $this->state['message']);
        $this->define('version', $this->service('config.app')->get('app', 'version'));
        
        $this->define('themeDark', $this->route('themeSwitch', array('theme' => 'dark')));
        $this->define('themeLight', $this->route('themeSwitch', array('theme' => 'light')));
        
        $this->filter('title', 'htmlentities|trim');
        $this->filter('message', 'htmlentities|trim');
    }

}