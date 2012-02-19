<?php
pload('app.AppView');

/**
 * HomeView
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package candice.view
 * @since 1.0-sofia
 */
class HomeView extends AppView {
    
    protected $data;
    
    public function data($data = null){
        if(func_num_args() == 1){
            $this->data = $data;
        }
        return $this->data;
    }

    protected function create() {
        $theme = $this->bucket->pick('session')->get('theme', 'dark');
        $this->template('home')->theme($theme);
        
        $rootUrl = $this->bucket->pick('config.app')->get('app', 'rootUrl');
        $this->define('rootUrl', $rootUrl . '/');
        $this->define('title', $this->data['title']);
        $this->define('message', $this->data['message']);
        $this->define('version', $this->bucket->pick('config.app')->get('app', 'version'));
        $this->define('themeDark', $rootUrl . $this->bucket->pick('router')->to('themeSwitch', array('theme' => 'dark')));
        $this->define('themeLight', $rootUrl . $this->bucket->pick('router')->to('themeSwitch', array('theme' => 'light')));
    }

}