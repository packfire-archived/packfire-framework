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
use Packfire\Exception\InvalidArgumentException;

/**
 * A validator that uses other validators
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.1-sofia
 */
class SerialValidator implements IValidator
{
    /**
     * A collection of validators to run on the value
     * @var array
     * @since 1.1-sofia
     */
    private $validators = array();

    /**
     * Create a new SerialValidator object
     * @since 1.1-sofia
     */
    public function __construct()
    {
    }

    /**
     * Add a new validator to the validation series
     * @param  IValidator               $validator The validator to be added
     * @throws InvalidArgumentException Thrown when $validator is an instance of itself.
     * @since 1.1-sofia
     */
    public function add($validator)
    {
        if ($validator === $this) {
            throw new InvalidArgumentException(
                'SerialValidator::add',
                '$validator',
                'not itself or inception may happen'
            );
        }
        $this->validators[] = $validator;
    }

    /**
     * Validate the value
     * @param  integer|double $value The value to validate
     * @return boolean        Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.1-sofia
     */
    public function validate($value)
    {
        $result = true;
        foreach ($this->validators as $validator) {
            /* @var $validator IValidator */
            $result = $validator->validate($value);
            if (!$result) {
                break;
            }
        }

        return $result;
    }
}
