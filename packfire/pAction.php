<?php
pload('packfire.IRunnable');

/**
 * An action class that combines the logic controller and view controller
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pAction implements IRunnable {
    
    /**
     * The controller for this action
     * @var pController
     */
    private $controller;
    
    /**
     * Create a new pAction
     * @param string $controller The name of the controller class to execute
     * @param string $view The name of the view class to output
     */
    public function __construct($controller, $view){
        pload('controller.' . $controller);
        $this->controller = new $controller();
        $this->controller->view($view);
    }
    
    /**
     * Get the controller
     * @return pController Returns the controller
     */
    public function controller(){
        return $this->controller;
    }
    
    /**
     * Execute the action  
     */
    public function run() {
        $this->controller->run();
    }
    
}