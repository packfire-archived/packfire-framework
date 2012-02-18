<?php
pload('app.AppView');

/**
 * HomeView
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package sofia.view
 * @since 1.0-sofia
 */
class HomeView extends AppView {
    
    protected $data;
    
    public function data($data = null){
        if(func_num_args() == 1){
            $this->data = $data;
        }
        return $this->data;
    }

    protected function create() {
        $this->template('home');
        
        $this->define('title', $this->data['title']);
        $this->define('message', $this->data['message']);
    }

}