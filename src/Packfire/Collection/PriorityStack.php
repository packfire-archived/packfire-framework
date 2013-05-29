<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Collection;

use Packfire\Collection\Stack;

/**
 * PriorityStack class
 *
 * A stack with priority
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class PriorityStack extends Stack
{
    /**
     * The comparator function to perform the sorting
     * @var Closure|callback
     * @since 1.0-sofia
     */
    private $comparator;

    /**
     * Create a new PriorityStack object
     * @param Closure|callback $comparator The comparator to perform the sort
     * @since 1.0-sofia
     * @codeCoverageIgnore
     */
    public function __construct($comparator)
    {
        parent::__construct();
        $this->comparator = $comparator;
    }

    /**
     * Add a item to the list.
     * @param mixed $item The item to be added.
     * @since 1.0-sofia
     */
    public function add($item)
    {
        parent::add($item);
        usort($this->array, $this->comparator);
    }

}
