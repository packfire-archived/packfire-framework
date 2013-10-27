<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Pack;

use Packfire\Controller\Controller as CoreController;
use Packfire\Exception\MissingDependencyException;
use Packfire\View\IView;

/**
 * The generic application controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Pack
 * @since 1.1-sofia
 */
abstract class Controller extends CoreController
{

    /**
     * Load and render the view for this controller
     * @param IView $view (optional) The view object to be rendered. If omitted,
     *           the view will be loaded using the caller method name.
     * @return mixed Returns the result of the view rendered. 
     * @throws MissingDependencyException Thrown when the $view is not an
     *              instance of IView or the view cannot be loaded.
     * @since 1.1-sofia
     */
    public function render($view = null)
    {
        if (func_num_args() == 0) {
            $dbt = debug_backtrace();
            $func = ucfirst($dbt[1]['function']);

            $name = get_class($this);
            if (substr($name, -10) == 'Controller') {
                $name = substr($name, 0, strlen($name) - 10);
            }

            $class = $name . $func . 'View';
            if (class_exists($class)) {
                $view = new $class();
            }
        }
        if ($view instanceof IView) {
            return parent::render($view);
        } else {
            throw new MissingDependencyException(
                'View not rendered because not found.' .
                ($class ? ' Looked for ' . $class . '.' : '')
            );
        }
    }
}
