<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Template\Mustache;

use Packfire\Template\ITemplateFile;
use Packfire\Template\Mustache\Template;
use Packfire\IO\File\File as RealFile;

/**
 * A mustache template file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template\Mustache
 * @since 1.1-sofia
 */
class TemplateFile extends Template implements ITemplateFile
{
    /**
     * Create a new TemplateFile object
     * @param File|string $file The file or pathname to the file
     * @since 1.1-sofia
     */
    public function __construct($file)
    {
        if ($file instanceof RealFile) {
            $file = $file->pathname();
        }
        parent::__construct(file_get_contents($file));
    }

}
