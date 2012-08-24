<?php
pload('packfire.controller.pController');
pload('packfire.exception.pMissingDependencyException');

/**
 * pAppController class
 * 
 * The generic application controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.pack
 * @since 1.1-sofia
 */
abstract class pAppController extends pController {
    
    /**
     * Load and render the view for this controller
     * @param IView $view (optional) The view object to be rendered. If omitted,
     *           the view will be loaded using the caller method name.
     * @throws pMissingDependencyException Thrown when the $view is not an
     *              instance of IView or the view cannot be loaded.
     * @since 1.1-sofia
     */
    public function render($view = null) {
        if(func_num_args() == 0){
            $dbt = debug_backtrace();
            $func = ucfirst($dbt[1]['function']);
            $name = get_class($this);
            if(substr($name, -10) == 'Controller'){
                $name = substr($name, 0, strlen($name) - 10);
            }
            $class = $name . $func . 'View';
            try{
                pload('view.' . strtolower($name) . '.' . $class);
            }catch(pMissingDependencyException $ex){
                try{
                    pload('view.' . $class);
                }catch(pMissingDependencyException $ex){
                    
                }
            }
            if(class_exists($class)){
                $view = new $class();
            }
        }
        if($view instanceof IView){
            parent::render($view);
        }else{
            throw new pMissingDependencyException(sprintf(
                'View not rendered because not found.'
                    . ' Looked for packages "%s" and "%s".',
                'view.' . strtolower($name) . '.' . $class,
                'view.' . $class
            ));
        }
    }
    
}