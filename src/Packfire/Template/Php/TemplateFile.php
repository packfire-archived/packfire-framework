<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Template\Php;

use Packfire\Template\ITemplateFile;
use Packfire\IO\File\File;
use Packfire\Collection\ArrayList;
use Packfire\Collection\Map;

/**
 * Render PHP files directly
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template
 * @since 1.1-sofia
 */
class TemplateFile implements ITemplateFile
{
    /**
     * Pathname to the PHP file
     * @var string
     * @since 1.1-sofia
     */
    private $file;

    /**
     * The template fields
     * @var Map
     * @since 1.1-sofia
     */
    private $fields;

    /**
     * Create a new PhpTemplateFile object
     * @param File|string $file The file or pathname to the file
     * @since 1.1-sofia
     */
    public function __construct($file)
    {
        if ($file instanceof File) {
            $file = $file->pathname();
        }
        $this->file = $file;
        $this->fields = new Map();
    }

    /**
     * Get the template fields
     * @return Map Returns the template fields hash map
     * @since 1.1-sofia
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Parses the template fields into the template
     * @return string Returns the parsed template
     * @since 1.1-sofia
     */
    public function parse()
    {
        ob_start();
        var_export($this->fields->toArray());
        include($this->file);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Set fields to the template
     * @param mixed $set The fields to be set
     * @since 1.1-sofia
     */
    public function set($set)
    {
        if (is_object($set) && !($set instanceof ArrayList)) {
            $set = get_object_vars($set);
        }
        if (is_array($set) || $set instanceof ArrayList) {
            foreach ($set as $key => $value) {
                $this->fields->add($key, $value);
            }
        }
    }

}
