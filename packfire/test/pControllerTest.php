<?php
pload('packfire.exception.pMissingDependencyException');

if(!class_exists('PHPUnit_Framework_TestCase')){
    throw new pMissingDependencyException('PHPUnit required, but not found'
            . ' when test cases are included.');
}

/**
 * pControllerTest abstract class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0-sofia
 */
abstract class pControllerTest extends PHPUnit_Framework_TestCase {
    
    /**
     * The controller to be tested.
     * 
     * Set a new instance of the controller in the setUp() method for PHPUnit.
     * 
     * @var pController
     * @since 1.0-sofia
     */
    protected $controller;
    
    /**
     * Run an action of the controller
     * @param string $action The name of the action to execute
     * @param array|pList $parameters The parameters to pass into the action
     * @param pRoute $route (optional) The route for the action to use
     * @return IAppResponse The response of the controller
     * @since 1.0-sofia
     */
    public function runAction($action, $parameters = array(), $route = null){
        $route = $route ? $route : new pRoute('', '');
        $this->controller->params()->append($parameters);
        $this->controller->run($route, $action);
        return $this->controller;
    }
    
}