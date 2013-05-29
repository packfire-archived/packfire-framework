<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Pack;

use Packfire\IO\File\Path;

/**
 * Performs template loading for application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Pack
 * @since 1.1-sofia
 */
class Template
{

    /**
     * Load a template from the template folder
     * @param  string                       $name Name of the template to load
     * @return \Packfire\Template\ITemplate Returns the template
     * @since 1.0-sofia
     */
    public static function load($name)
    {
        $path = __APP_ROOT__ . '/src' . DIRECTORY_SEPARATOR . str_replace(array('_', '\\', '/'), DIRECTORY_SEPARATOR, $name);

        // parsers
        $extensions = array(
            'html' => 'Packfire\Template\Mustache\TemplateFile',
            'htm' => 'Packfire\Template\Mustache\TemplateFile',
            'mustache' => 'Packfire\Template\Mustache\TemplateFile',
            'php' => 'Packfire\Template\Php\TemplateFile'
        );

        $template = null;
        if ($extension = Path::extension($path)) {
            $class = $extensions[$extension];
            $template = new $class($path);
        } else {
            foreach ($extensions as $type => $class) {
                if (is_file($path . '.' .  $type)) {
                    $template = new $class($path . '.' .  $type);
                    break;
                }
            }
        }

        return $template;
    }
}
