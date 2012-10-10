<?php
namespace Packfire\Welcome;

use Packfire\Application\Pack\View;
use Packfire\Template\Mustache\TemplateFile;
use LightTheme;
use DarkTheme;

/**
 * HomeIndexView class
 * 
 * View for the homepage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Welcome
 * @since 1.0-sofia
 */
class HomeIndexView extends View {
    
    protected function create() {
        $theme = $this->service('session')->get('theme', 'dark');
        if(!in_array($theme, array('dark', 'light'))){
            $theme = 'light';
        }
        $template = new TemplateFile(__DIR__ . '/HomeIndexView.html');
        $this->theme($theme == 'dark' ? new DarkTheme() : new LightTheme())
            ->template($template);
        
        $rootUrl = $this->route('home');
        $this->define('rootUrl', $rootUrl);
        $this->define('title', $this->state['title']);
        $this->define('message', $this->state['message']);
        $this->define('version', __PACKFIRE_VERSION__);
        
        $this->define('themeDark', $this->route('themeSwitch', array('theme' => 'dark')));
        $this->define('themeLight', $this->route('themeSwitch', array('theme' => 'light')));
        
        $this->filter('title', 'htmlentities|trim');
        $this->filter('message', 'htmlentities|trim');
    }

}