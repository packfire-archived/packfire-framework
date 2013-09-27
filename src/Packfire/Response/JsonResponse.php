<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Response;

use Packfire\Application\Http\Response as HttpResponse;
use Packfire\Data\Serialization\JsonSerializer;
use Packfire\Response\IResponseFormat;

/**
 * A Response that indicates that the response is JSON
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Response
 * @since 1.0-sofia
 */
class JsonResponse extends HttpResponse implements IResponseFormat
{
    /**
     * Create a new JsonResponse object
     * @param mixed $object The JSON object that will be responded to the
     *                      client with
     * @param string $callback (optional) The callback for JSONP calls
     * @since 1.0-sofia
     */
    public function __construct($object, $callback = null)
    {
        parent::__construct();
        $serializer = new JsonSerializer();
        if ($callback) {
            $this->headers()->add('Content-Type', 'text/javascript');
            $this->body($callback . '(' . $serializer->serialize($object) . ')');
        } else {
            $this->headers()->add('Content-Type', 'application/json');
            $this->body($serializer->serialize($object));
        }
    }
}
