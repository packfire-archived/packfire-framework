<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Welcome;

use Packfire\Application\Pack\View;
use Packfire\Template\Mustache\TemplateFile;
use Packfire\Welcome\LightTheme;
use Packfire\Welcome\DarkTheme;

/**
 * View for the homepage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
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