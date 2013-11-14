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

/**
 * View for the homepage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Welcome
 * @since 1.0-sofia
 */
class HomeGetIndexView extends View
{
    protected function create()
    {
        $template = new TemplateFile(__DIR__ . '/HomeGetIndexView.html');
        $this->template($template);

        $theme = $this->ioc['session']->get('theme', 'dark');
        if (!in_array($theme, array('dark', 'light'))) {
            $theme = 'light';
        }
        $this->define('style', $theme);

        $rootUrl = $this->route('home');
        $this->define('rootUrl', $rootUrl);

        $this->define('themeDark', $this->route('themeSwitch', array('theme' => 'dark')));
        $this->define('themeLight', $this->route('themeSwitch', array('theme' => 'light')));
    }
}
