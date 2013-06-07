<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Validator;

use Packfire\Validator\IValidator;
use Packfire\Collection\ArrayList;

/**
 * Checks if value matches values provided
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class MatchValidator implements IValidator
{
    /**
     * The matches to validate against
     * @var array|ArrayList|mixed
     * @since 1.0-sofia
     */
    private $matches;

    /**
     * Create a new MatchValidator object
     * @param array|ArrayList|mixed $matches An array of values or the value to match against
     * @since 1.0-sofia
     */
    public function __construct($matches)
    {
        if ($matches instanceof ArrayList) {
            $matches = $matches->toArray();
        }
        $this->matches = $matches;
    }

    /**
     * Validate the value
     * @param  mixed   $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value)
    {
        if (is_array($this->matches)) {
            return in_array($value, $this->matches, true);
        } else {
            return $this->matches === $value;
        }
    }
}
