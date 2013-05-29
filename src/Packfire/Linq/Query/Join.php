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

use Packfire\Linq\IQuery;
use Packfire\Collection\Map;

/**
 * A LINQ Join query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class Join implements IQuery
{
    private $joinCollection;
    private $innerKeySelector;
    private $outerKeySelector;
    private $resultSelector;

    /**
     * Create a new Join object
     * @param mixed $joinCollection
     * @param mixed $innerKey
     * @param mixed $outerKey
     * @param mixed $resultSelector
     * @since 1.0-sofia
     */
    public function __construct($joinCollection, $innerKey, $outerKey, $resultSelector)
    {
        $this->joinCollection = $joinCollection;
        $this->innerKeySelector = $innerKey;
        $this->outerKeySelector = $outerKey;
        $this->resultSelector = $resultSelector;
    }

    /**
     * Execute the query
     * @param  array $collection The collection to execute upon
     * @return mixed Returns the result after the query execution
     * @since 1.0-sofia
     */
    public function run($collection)
    {
        $result = array();

        $outerKeyCache = new Map();
        foreach ($this->joinCollection as $k => $joinElement) {
            $outerKey = call_user_func($this->outerKeySelector, $joinElement);
            $outerKeyCache->add($k, $outerKey);
        }

        foreach ($collection as $element) {
            $innerKey = call_user_func($this->innerKeySelector, $element);
            foreach ($this->joinCollection as $k => $joinElement) {
                if ($innerKey === $outerKeyCache[$k]) {
                    $result[] = call_user_func($this->resultSelector, $element, $joinElement);
                }
            }
        }

        return $result;
    }

}
