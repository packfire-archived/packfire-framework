<?php

/**
 * tSampleModel class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.1-sofia
 */
class tSampleModel {
    
    public $title;
    
    public $call = false;
    
    function call(){
        $this->call = true;
    }
    
    static function callStatic(){
        return 'test';
    }
    
}
