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
abstract class pController implements IRunnable {
    
    /**
     * 
     * @var IView
     */
    private $view;
    
    public function view($view = null){
        if(func_num_args() == 1){
            $this->view = $view;
        }
        return $this->view;
    }
    
    public function model($model){
        pload('model.' . $model);
    }
    
    public abstract function run();
    
}