<?php
pload('packfire.IRunnable');

/**
 * The generic controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
abstract class pController {
    
    protected $restful = true;
    
    public function __construct(){
        
    }
    
    public function render($view){
        
    }
    
    public function model($model){
        pload('model.' . $model);
        $obj = new $model();
        return $obj;
    }
    
    /**
     * Run the controller with the route
     * @param pHttpClient $client
     */
    public function run($client){
        $route = $client->request()->route();
        list(, $action) = explode(':', $route->actual());
        if(!$action){
            $action = 'index';
        }
    }
    
}