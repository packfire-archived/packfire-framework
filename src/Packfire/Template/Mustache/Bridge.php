<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Template\Mustache;

use Packfire\Template\Mustache\Mustache;
use Packfire\Application\Pack\Template as AppTemplate;
use Packfire\Collection\ArrayList;
use Packfire\View\IView;
use Packfire\FuelBlade\IConsumer;

/**
 * Mustache bridge that allows loading of partials from Application Templates
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template\Mustache
 * @since 1.1-sofia
 */
class Bridge extends Mustache implements IConsumer
{
    protected $ioc;

    /**
     * Get the partial by name and add to the buffer
     * @param string $name Name of the partial
     * @since 1.0-sofia
     */
    protected function partial($name, $scope)
    {
        /* @var $template ITemplate */
        if (class_exists($name)) {
            $object = new $name();
            if($object instanceof IView){
                $object($this->ioc);
                $object->state($scope);
                $this->buffer .= $object->render();
            }
        } else {
            $template = AppTemplate::load($name);
            if ($template) {
                // Partial will use scope parameters only because partial does not know what is on top
                $template->set($scope);
                $this->buffer .= $template->parse();
            }
        }
    }

    protected function loadParameters()
    {
        if ($this->parameters instanceof ArrayList) {
            $this->parameters = $this->parameters->toArray();
        }
        if (count($this->parameters) == 0) {
            $this->parameters = null;
        }
    }

    public function __invoke($container)
    {
        $this->ioc = $container;
    }
}
