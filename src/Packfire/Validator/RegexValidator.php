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
use Packfire\Text\Regex\Regex;

/**
 * Validation through regular expression match
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class RegexValidator implements IValidator
{
    /**
     * The regular expression
     * @var string
     * @since 1.0-sofia
     */
    private $match;

    /**
     * Create a new RegexValidator object
     * @param string $match The regular expression
     * @since 1.0-sofia
     */
    public function __construct($match)
    {
        $this->match = $match;
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
        $regex = new Regex($this->match);

        return $regex->matches($value);
    }

}
