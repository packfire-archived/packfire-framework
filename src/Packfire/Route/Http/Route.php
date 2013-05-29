<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Route\Http;

use Packfire\Route\Route as CoreRoute;
use Packfire\Net\Http\Method as HttpMethod;
use Packfire\Collection\Map;
use Packfire\Template\Template;
use Packfire\Route\Validator;

/**
 * A HTTP route entry
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route\Http
 * @since 1.0-elenor
 */
class Route extends CoreRoute
{
    /**
     * The HTTP method that this URL route will cater for. Defaults to GET.
     * @var string|pist|array
     * @since 1.0-elenor
     */
    protected $httpMethod = HttpMethod::GET;

    /**
     * The rewritten relative-to-host URL
     * @var string
     * @since 1.0-sofia
     */
    protected $rewrite;

    /**
     * Create a new Route object
     * @param string    $name The name of the route
     * @param array|Map $data The configuration data entry
     * @since 1.0-elenor
     */
    public function __construct($name, $data)
    {
        if (!($data instanceof Map)) {
            $data = new Map($data);
        }
        parent::__construct($name, $data);
        $this->rewrite = $data->get('rewrite');
        $this->httpMethod = $data->get('method');
    }

    /**
     * Get the HTTP method for this URL route
     * @return string Returns the HTTP method
     * @since 1.0-elenor
     */
    public function httpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Get the rewritten relative-to-host URL
     * @return string Returns the relative URL
     * @since 1.0-elenor
     */
    public function rewrite()
    {
        return $this->rewrite;
    }

    /**
     * Check whether the route matches the request
     * @param  Packfire\Application\Http\Request $request The locator requested by the client
     * @return boolean                           Returns true if the route matches, false otherwise
     * @since 1.0-elenor
     */
    public function match($request)
    {
        $url = $request->pathInfo();
        $method = strtolower($request->method());

        $validation = false;
        // check whether HTTP method matches for RESTful routing
        if(!$this->httpMethod() ||
                (is_string($this->httpMethod)
                && $this->httpMethod == $method)
                || (is_array($this->httpMethod)
                && in_array($method, $this->httpMethod))){
            if ($this->params) {
                if ($url == $this->rewrite) {
                    $urlMatch = true;
                    $urlData = array();
                } else {
                    $template = new Template($this->rewrite);
                    $tokens = $template->tokens();
                    foreach ($tokens as $token) {
                        $template->fields()->add($token,
                                '(?<' . $token . '>.+)');
                    }
                    // perform the URL matching
                    $urlMatch = preg_match('`^' . $template->parse() .
                            '[/]{0,1}$`isU', $url, $urlData);
                    $urlData = array_intersect_key($urlData, array_flip($tokens->toArray()));
                }
                if ($urlMatch) {
                    $data = array();
                    foreach ($urlData as $key => $value) {
                        $data[$key] = $value;
                    }
                    $validationKeys = $this->rules->select(array_keys($urlData));

                    $params = array();
                    $validator = new Validator($validationKeys);
                    $validation = $validator->validate($data, $params);
                    if ($validation) {
                        $params += $request->get()->toArray();
                        if ($method == 'post') {
                            $params += $request->post()->toArray();
                        }
                        $this->params = new Map($params);
                        if ($this->remap->count() > 0) {
                            $this->remapParam($this->remap, $params);
                        }
                        $this->remap = new Map($params);
                    }

                }
            }
        }

        return $validation;
    }

}
