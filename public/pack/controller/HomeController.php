<?php
pload('app.AppController');
pload('view.HomeView');

/**
 * Handles interaction for home
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package candice.controller
 * @since 1.0-sofia
 */
class HomeController extends AppController {
    
    function doIndex(){
        $data = array(
            'title' => 'Bring the fire around in a pack.',
            'message' => 'Packfire is a clean and well thought web framework for developers of all walks to scaffold and bring up websites quickly and hassle-free. You\'ll be surprised at how fast you can build a web application with a pack of fire.'
        );
        $view = new HomeView();
        $view->data($data);
        return $this->render($view);
    }
    
}