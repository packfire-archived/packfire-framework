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

    public function build($view)
    {
        $view($this->ioc);
        $view->state($this->state);
        $output = $view->render();
        if (isset($this->ioc['response']) && $this->ioc['response']) {
            $this->ioc['response']->body($output);
        }
        return $output;
    }
}
