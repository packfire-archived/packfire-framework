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

use Packfire\Application\Pack\Controller;

/**
 * Switches between themes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Welcome
 * @since 1.0-sofia
 */
class ThemeSwitchController extends Controller
{
    public function index($theme)
    {
        $this->ioc['session']->set('theme', $theme);
        $this->redirect($this->route('home'));
    }

}
