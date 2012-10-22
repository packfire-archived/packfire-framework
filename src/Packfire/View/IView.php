<?php
namespace Packfire\View;

/**
 * IView interface
 * 
 * View interface that provides an output
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\View
 * @since 1.0-sofia
 */
interface IView {

    /**
     * Generate the output of this view
     * @return string Returns the generated output
     * @since 1.0-sofia
     */
    public function render();
    
    /**
     * Set the state from the controller to the view
     * @param Map $state The state of the controller passed to the view.
     * @since 1.0-sofia
     */
    public function state($state);
    
}