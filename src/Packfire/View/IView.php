<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\View;

use Packfire\FuelBlade\ConsumerInterface;

/**
 * View interface that provides an output
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\View
 * @since 1.0-sofia
 */
interface IView extends ConsumerInterface
{
    /**
     * Generate the output of this view
     * @return string Returns the generated output
     * @since 1.0-sofia
     */
    public function render();
}
