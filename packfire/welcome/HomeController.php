<?php
pload('packfire.controller.pController');
pload('HomeIndexView');

/**
 * HomeController class
 * 
 * Handles interaction for home
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.welcome
 * @since 1.0-sofia
 */
class HomeController extends pController {
    
    function doIndex(){
        $this->state = array(
            'title' => 'Bring the fire around in a pack.',
            'message' => 'Packfire is a clean and well thought web framework for developers of all walks to scaffold and bring up websites quickly and hassle-free. You\'ll be surprised at how fast you can build a web application with a pack of fire.'
        );
        $this->render(new HomeIndexView());
    }
    
}