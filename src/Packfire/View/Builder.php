<?php

namespace Packfire\View;

use Packfire\FuelBlade\ContainerInterface;

class Builder implements BuilderInterface
{
    protected $ioc;

    public function __construct(ContainerInterface $container)
    {
        $this->ioc = $container;
    }

    public function create()
    {
        $trace = debug_backtrace();
        $caller = next($trace);
        unset($trace);
        $class = $caller['class'];
        // remove the controller name at the back
        if (substr($class, -10) == 'Controller') {
            $class = substr($class, 0, strlen($class) - 10);
        }
        $class .= ucfirst($caller['function']) . 'View';
        if (class_exists($class)) {
            return new $class();
        }
        return array();
    }

    public function build($view)
    {
        $view($this->ioc);
        $output = $view->render();
        if (isset($this->ioc['response']) && $this->ioc['response']) {
            $this->ioc['response']->body($output);
        }
        return $output;
    }
}
