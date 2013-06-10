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

use Packfire\Application\Pack\Theme;
use Packfire\Application\Pack\Template;
use Packfire\View\View as CoreView;
use Packfire\FuelBlade\ConsumerInterface;

/**
 * View class
 *
 * The generic application view class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Pack
 * @since 1.1-sofia
 */
abstract class View extends CoreView
{
    public function render()
    {
        // set the default template as the name of the view class
        $template = str_replace('\\', DIRECTORY_SEPARATOR, get_class($this));
        $this->template($template);
        return parent::render();
    }

    /**
     * Set the template for the view class
     * @param ITemplate|string $template The template or name of the template
     *          to set for the view class.
     * @return View Returns the object for chaining
     * @since 1.1-sofia
     */
    protected function template($template)
    {
        if (is_string($template)) {
            $template = Template::load($template);
            if ($template instanceof ConsumerInterface) {
                $template($this->ioc);
            }
        }

        return parent::template($template);
    }

    /**
     * Set the theme for the view class
     * @param Theme|string $theme The theme or the name of the theme class to
     *          set to the view class
     * @return View Returns the object for chaining
     * @since 1.1-sofia
     */
    protected function theme($theme)
    {
        if (is_string($theme)) {
            $theme = Theme::load($theme);
            if ($theme instanceof ConsumerInterface) {
                $theme($this->ioc);
            }
        }

        return parent::theme($theme);
    }
}
