<?php
namespace Packfire\Response;

use JsonResponse;
use PhpSerializeResponse;
use XmlResponse;
use YamlResponse;

/**
 * DataResponse class
 * 
 * A sugar candy for response creation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Response
 * @since 1.1-sofia
 */
class DataResponse {
    
    /**
     * The format type of the response
     * @var string
     * @since 1.1-sofia
     */
    private $type;
    
    /**
     * Create a new DataResponse object
     * @param string $type The format type
     * @since 1.1-sofia
     */
    public function __construct($type){
        $this->type = $type;
    }
    
    /**
     * Build the response based on the object and options provided.
     * @param mixed $object The object to be encapsulated
     * @param mixed $options (optional) Any other options
     * @return IResponseFormat Returns the formatted response
     * @since 1.1-sofia
     */
    public function build($object, $options = null){
        $response = null;
        switch($this->type){
            case 'json':
                $response = new JsonResponse($object);
                break;
            case 'jsonp':
                $response = new JsonResponse($object, $options);
                break;
            case 'xml':
                $response = new XmlResponse($object);
                break;
            case 'yaml':
                $response = new YamlResponse($object);
                break;
            default:
                $response = new PhpSerializeResponse($object);
                break;
        }
        return $response;
    }
    
    /**
     * Create a response based on the format
     * @param mixed $object The object to be returned to the client
     * @param string $format The format of response (json, jsonp, xml, yaml, php)
     * @param mixed $options (optional) Any other options
     * @return IResponseFormat Returns a response format
     * @since 1.1-sofia
     */
    public static function create($object, $format, $options = null){
        $response = new self($format);
        return $response->build($object, $options);
    }
    
}