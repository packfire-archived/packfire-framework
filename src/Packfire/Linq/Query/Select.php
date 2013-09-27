<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Linq\Query;

use Packfire\Linq\Query\Worker;

/**
 * A LINQ select query to Re-map the values
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class Select extends Worker
{
    /**
     * Execute the query
     * @param  array $collection The collection to execute upon
     * @return array Returns the resulting array after the query execution
     * @since 1.0-sofia
     */
    public function run($collection)
    {
        $result = array();
        $worker = $this->worker();
        foreach ($collection as $element) {
            $result[] = $worker($element);
        }

        return $result;
    }
}
