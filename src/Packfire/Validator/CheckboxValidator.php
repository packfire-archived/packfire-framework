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
 * Form Checkbox validation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class CheckboxValidator implements IValidator
{
    /**
     * State of the check box
     * True is checked, False is unchecked
     * @var boolean
     * @since 1.0-sofia
     */
    private $state;

    /**
     * Create a new CheckboxValidator object
     * @param boolean $state State of the checkbox
     * @since 1.0-sofia
     */
    public function __construct($state)
    {
        $this->state = $state;
    }

    /**
     * Validate the value
     * @param  integer|double $value The value to validate
     * @return boolean        Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value)
    {
        return (bool) $value === $this->state;
    }
}
