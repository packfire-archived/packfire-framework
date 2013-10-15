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

use PHPUnit_Framework_TestCase as TestCase;
use Packfire\Welcome\HelloWorldView;

class ControllerTest extends TestCase
{
    /**
     * @expectedException Packfire\Exception\MissingDependencyException
     */
    public function testRender()
    {
        $controller = $this->getMockForAbstractClass('Packfire\\Application\\Pack\\Controller', array('render'));
        $controller->render();
    }

    public function testRender2()
    {
        $controller = $this->getMockForAbstractClass('Packfire\\Application\\Pack\\Controller', array('render'));
        $result = $controller->render(new HelloWorldView());
        $this->assertEquals('Hello World', $result);
    }
}
