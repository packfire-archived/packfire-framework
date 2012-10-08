<?php
namespace Packfire\Welcome;

use Packfire\Application\Pack\View;

/**
 * HelloWorldView View
 * 
 * A simple hello-world
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Welcome
 * @since 1.0-elenor
 */
class HelloWorldView extends View {
    
    protected function create(){
        echo 'Hello World';
    }

}