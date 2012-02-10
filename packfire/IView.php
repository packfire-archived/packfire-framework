<?php

/**
 * View interface that provides an output
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
interface IView {

    /**
     * Generate the output of this view
     * @return string 
     */
    public function output();
    
}