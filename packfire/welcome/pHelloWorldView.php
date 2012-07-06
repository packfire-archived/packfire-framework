<?php
pload('app.AppView');

/**
 * pHelloWorldView View
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.welcome
 * @since 1.0-elenor
 */
class pHelloWorldView extends pView {
    
    protected function create(){
        echo 'Hello World';
    }

}