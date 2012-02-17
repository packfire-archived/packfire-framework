<?php
pload('app.AppController');
pload('view.HomeView');

/**
 * Handles interaction for home
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package sofia.controller
 * @since 1.0-sofia
 */
class HomeController extends AppController {
    
    function doIndex(){
        $data = array(
            'title' => 'Packfire Sofia',
            'message' => 'is really awesome!'
        );
        $view = new HomeView();
        $view->data($data);
        return $this->render($view);
    }
    
    function doHelloWorld(){
        
    }
    
}