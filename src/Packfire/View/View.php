<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\View;

use Packfire\Collection\IList;
use Packfire\View\IView;
use Packfire\Collection\ArrayList;
use Packfire\Collection\Map;
use Packfire\Core\ObjectObserver;
use Packfire\Exception\InvalidArgumentException;
use Packfire\FuelBlade\IConsumer;

/**
 * The generic view class.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\View
 * @since 1.0-sofia
 */
abstract class View implements IView, IConsumer
{
    /**
     * The IoC Container
     * @var \Packfire\FuelBlade\Container
     * @since 2.1.0
     */
    protected $ioc;

    /**
     * The state that is passed from the controller
     * @var \Packfire\Collection\Map
     * @since 1.0-sofia
     */
    protected $state;

    /**
     * The fields in the view defined
     * @var \Packfire\Collection\Map
     * @since 1.0-sofia
     */
    private $fields;

    /**
     * The filters for the output fields
     * @var \Packfire\Collection\ArrayList
     * @since 1.0-sofia
     */
    private $filters;

    /**
     * The template for the view to render
     * @var \Packfire\Template\ITemplate
     * @since 1.0-sofia
     */
    private $template;

    /**
     * The template for the view to render
     * @var \Packfire\View\Theme
     * @since 1.0-sofia
     */
    private $theme;

    /**
     * Create a new View object
     * @since 1.0-sofia
     */
    public function __construct()
    {
        $this->state = new Map();
        $this->fields = new Map();
        $this->filters = new ArrayList();
    }

    /**
     * Define a template field to populate.
     * @param  string|array|Map $key   Name of the field
     * @param  mixed            $value (optional) Set the template field value
     * @return mixed            Returns the current value set at $key if $value is not set.
     * @since 1.0-sofia
     */
    public function define($key, $value = null)
    {
        if (func_num_args() == 1) {
            if (is_string($key)) {
                return $this->fields[$key];
            } else {
                $this->fields->append($key);
            }
        } else {
            $this->fields[$key] = $value;
        }
    }

    /**
     * Bind a object property to a template field.
     * As the object property gets updated, the template field gets updated too.
     *
     * @param string                        $key      The template field to bind to
     * @param \Packfire\Core\ObjectObserver $object   The object to be binded
     * @param string                        $property The property of the object to bind to the
     *                  template field.
     * @throws InvalidArgumentException Thrown when $object is not an instance of ObjectObserver
     * @since 1.1-sofia
     */
    public function bind($key, $object, $property)
    {
        if ($object instanceof ObjectObserver) {
            $view = $this;
            $object->on('change', function($src, $eventArgs) use ($view, $key, $property) {
                if ($eventArgs[0] == $property) {
                    $view->define($key, $eventArgs[1]);
                }
            });
        } else {
            throw new InvalidArgumentException('View::bind', 'object',
                    'an instance of ObjectObserver', dtype($object));
        }
    }

    /**
     * Set filters to a parameter.
     *
     * @param string                       $name   Name of the parameter to add filters to
     * @param Closure|callback|array|IList $filter The controller filter,
     *              closure or callback that will process the parameter.
     *              If $filter is an array the method will run through the array
     *              recursively.
     * @since 1.0-sofia
     */
    protected function filter($name, $filter)
    {
        if (is_string($filter)) {
            $ex = explode('|', $filter);
            if (count($ex) > 1) {
                $filter = $ex;
            }
        }
        if (is_array($filter) || $filter instanceof IList) {
            foreach ($filter as $f) {
                $this->filter($name, $f);
            }
        } else {
            $this->filters[] = array($name, $filter);
        }
    }

    /**
     * Set the state from the controller to the view
     * @param Map $state The state of the controller passed to the view.
     * @since 1.0-sofia
     */
    public function state($state)
    {
        $this->state = $state;
    }

    /**
     * Set the template used by the view
     * @param  ITemplate $template The template to use
     * @return View      Returns an instance of self for chaining.
     * @since 1.0-sofia
     */
    protected function template($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Set the theme used by the view
     * @param  Theme $theme The theme to use
     * @return View  Returns an instance of self for chaining.
     * @since 1.0-sofia
     */
    protected function theme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get a specific routing URL from the router service
     * @param  string $key    The routing key
     * @param  array  $params (optionl) URL Parameters
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    protected function route($key, $params = array())
    {
        $router = $this->ioc['router'];
        $url = $router->to($key, $params);
        if (strlen($url) > 0 && $url[0] == '/' && isset($this->ioc['config'])) {
            $url = $this->ioc['config']->get('app', 'rootUrl') . $url;
        }

        return $url;
    }

    /**
     * Prepare and create the view fields
     * @since 1.0-sofia
     */
    abstract protected function create();

    /**
     * Get the output of the view.
     * @return string Returns the output of this view.
     * @since 1.0-sofia
     */
    public function render()
    {
        ob_start();
        $this->create();
        $output = ob_get_contents();
        ob_end_clean();
        if ($this->theme) {
            // render the theme
            $this->theme->render();
            // forward the theme fields to the view
            foreach ($this->theme->fields() as $key => $value) {
                $this->define('theme.' . $key, $value);
            }
        }

        if ($this->template) {
            foreach ($this->filters as $filter) {
                $name = $filter[0];
                $filter = $filter[1];
                $value = $this->fields[$name];
                if (class_exists($filter)) {
                    $filter = new $filter();
                }
                if (is_callable($filter)) {
                    $value = $filter($value);
                }
                $this->fields[$name] = $value;
            }

            // allow you to use another view in a view.
            foreach ($this->fields as $key => $field) {
                if ($field instanceof IView) {
                    $this->fields[$key] = $field->render();
                }
            }
            $this->template->set($this->fields);
            if ($output) {
                $this->template->fields()->add('view.output', $output);
            }
            $output = $this->template->parse();
        }

        return $output;
    }

    public function __invoke($c)
    {
        $this->ioc = $c;

        return $this;
    }

}
