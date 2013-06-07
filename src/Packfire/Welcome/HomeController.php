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
use Packfire\Welcome\HomeIndexView;

/**
 * Handles interaction for home
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Welcome
 * @since 1.0-sofia
 */
class HomeController extends Controller
{
    public function message()
    {
        $this->state = array(
            'title' => 'Bring the fire around in a pack.',
            'message' => 'Packfire is a clean and well thought web framework for developers of all walks to scaffold and bring up websites quickly and hassle-free. You\'ll be surprised at how fast you can build a web application with a pack of fire.'
        );
    }

    public function getIndex()
    {
        $this->message();
        $this->render(new HomeIndexView());
    }

    public function cliIndex()
    {
        $this->message();
        echo 'Packfire Framework'
                . "\n" . '-----------------------------' . "\n\n";
        echo $this->state['message'] . "\n";
    }
}
