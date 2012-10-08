<?php
namespace Packfire\Appliation\Pack;

use Packfire\Controller\Controller as CoreController;
use Packfire\Exception\MissingDependencyException;
use Packfire\Text\Inflector;
use Packfire\Text\Text;
use Packfire\View\IView;

/**
 * Controller class
 * 
 * The generic application controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Appliation\Pack
 * @since 1.1-sofia
 */
abstract class Controller extends CoreController {
    
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
            $func2 = null;
            if(($firstUpper = Inflector::firstUpperCase($dbt[1]['function'])) !== false){
                $func2 = substr($dbt[1]['function'], $firstUpper);
            }
            
            $name = get_class($this);
            if(substr($name, -10) == 'Controller'){
                $name = substr($name, 0, strlen($name) - 10);
            }
            
            // todo autoloading
            $class = $name . $func . 'View';
            $tries = array(
                'view.' . strtolower($name) . '.' . $class,
                'view.' . $class
            );
            $class2 = null;
            if($func2){
                $class2 = $name . $func2 . 'View';
                $tries[] = 'view.' . strtolower($name) . '.' . $class2;
                $tries[] = 'view.' . $class2;
            }
            foreach($tries as $try){
                try{
                    pload($try);
                    break;
                }catch(MissingDependencyException $ex){

                }
            }
            if(class_exists($class)){
                $view = new $class();
            }elseif(class_exists($class2)){
                $view = new $class2();
            }
        }
        if($view instanceof IView){
            parent::render($view);
        }else{
            throw new MissingDependencyException(sprintf(
                'View not rendered because not found.'
                    . ' Looked for packages %s.',
                Text::listing($tries)
            ));
        }
    }
    
}