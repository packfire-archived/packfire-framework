<?php
pload('packfire.controller.pController');

/**
 * The generic application controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.app
 * @since 1.0-sofia
 */
abstract class AppController extends pController {
    
    public function render($view = null) {
        if(func_num_args() == 0){
            $dbt = debug_backtrace();
            $func = $dbt[1]['function'];
            if(substr($func, 0, 2) == 'do'){
                $func = substr($func, 2);
            }
            $name = get_class($this);
            if(substr($name, -10) == 'Controller'){
                $name = substr($name, 0, strlen($name) - 10);
            }
            $class = $name . ucfirst($func) . 'View';
            pload('view.' . strtolower($name) . '.' . $class);
            pload('view.' . $class);
            if(class_exists($class)){
                $view = new $class();
            }
        }
        parent::render($view);
    }
    
}