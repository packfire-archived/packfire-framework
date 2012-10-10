<?php
namespace Packfire\Response;

use Packfire\Application\Http\Response as HttpResponse;
use Packfire\Response\IResponseFormat;

/**
 * PhpSerializeResponse class
 * 
 * A response that serializes the object
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Response
 * @since 1.1-sofia
 */
class PhpSerializeResponse extends HttpResponse implements IResponseFormat {
    
    /**
     * Create a new PhpSerializeResponse object
     * @param mixed $object The object that will be encoded and sent to the
     *                      client
     * @since 1.1-sofia
     */
    public function __construct($object) {
        parent::__construct();
        $this->headers()->add('Content-Type', 'text/plain');
        $this->body(serialize($object));
    }

}