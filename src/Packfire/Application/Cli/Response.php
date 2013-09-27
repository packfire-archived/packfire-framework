<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Cli;

use Packfire\Application\IAppResponse;

/**
 * CLI Response to the client
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-elenor
 */
class Response implements IAppResponse
{
    /**
     * The exit code for the application response
     * @var integer
     * @since 1.0-elenor
     */
    private $output;

    /**
     * Get or set the exit code of the response
     * @param  integer $output (optional) Set the exit code
     * @return integer Returns the exit code
     * @since 1.0-elenor
     */
    public function output($output = null)
    {
        if (func_num_args() == 1) {
            $this->output = $output;
        }

        return $this->output;
    }
}
