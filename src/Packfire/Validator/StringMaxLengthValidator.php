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

/**
 * String maximum length validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class StringMaxLengthValidator implements IValidator
{
    /**
     * The maximum length of the string
     * @var string
     * @since 1.0-sofia
     */
    private $length;

    /**
     * Create a StringMaxLengthValidator object
     * @param string $length The minimum length of the string
     * @since 1.0-sofia
     */
    public function __construct($length)
    {
        $this->length = $length;
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
        return strlen($value) <= $this->length;
    }

}
