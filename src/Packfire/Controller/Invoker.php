<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Controller;

use Packfire\Core\ActionInvoker;
use Packfire\Application\Pack\Template;
use Packfire\FuelBlade\ConsumerInterface;

/**
 * Controller Access Invoker
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Controller
 * @since 1.0-sofia
 */
class Invoker implements ConsumerInterface
{
    /**
     * The IoC Container
     * @var \Packfire\FuelBlade\Container
     * @since 2.1.0
     */
    private $ioc;

    /**
     * The package name
     * @var string
     * @since 1.0-sofia
     */
    private $package;

    /**
     * The action to load
     * @var string
     * @since 1.0-sofia
     */
    private $action;

    /**
     * Create a new Invoker object
     * @param string $package The package to load the class
     * @param string $action  The action to be loaded
     * @since 1.0-sofia
     */
    public function __construct($package, $action)
    {
        $this->package = $package;
        $this->action = $action;
    }

    /**
     * Perform the loading process
     * @return boolean Returns true if loaded successfully, false otherwise.
     * @since 1.0-sofia
     */
    public function load()
    {
        $response = $this->ioc['response'];
        $class = $this->package;
        if (is_string($class)) {
            if (false !== strpos($class, '.')) { // check if there is an extension
                $template = Template::load($class);
                if ($template) {
                    $response->body($template->parse());
                } else {
                    return false;
                }
            } elseif (class_exists($class)) {
                $isView = self::classInstanceOf($class, 'Packfire\View\IView');
                if ($isView) {
                    /* @var $view \Packfire\View\View */
                    $view = new $class();
                    if ($view instanceof ConsumerInterface) {
                        $view($this->ioc);
                    }
                    $output = $view->render();
                    $response->body($output);
                } else {
                    $controller = new $class();
                    if ($controller instanceof ConsumerInterface) {
                        $controller($this->ioc);
                    }
                    if ($controller instanceof Controller) {
                        $controller->actionRun($this->action);
                    } else {
                        $params = isset($this->ioc['route']) ? $this->ioc['route']->params : array();
                        $actionInvoker = new ActionInvoker(array($controller, $this->action));
                        $this->ioc['response'] = $actionInvoker->invoke($params);
                    }
                }
            } else {
                // oops! the class is really not found (:
                return false;
            }
        } else {
            // oops! no idea what you've given me as $class
            return false;
        }

        return true;
    }

    protected static function classInstanceOf($className, $search)
    {
        $classOnly = !interface_exists($search);
        $class = new \ReflectionClass($className);
        if (!$class) {
            return false;
        }
        do {
            $name = $class->getName();
            if ($search == $name) {
                return true;
            }
            if (!$classOnly) {
                $interfaces = $class->getInterfaceNames();
                if (is_array($interfaces) && in_array($search, $interfaces)) {
                    return true;
                }
            }
            $class = $class->getParentClass();
        } while ($class);

        return false;
    }

    public function __invoke($container)
    {
        $this->ioc = $container;
        return $this;
    }
}
