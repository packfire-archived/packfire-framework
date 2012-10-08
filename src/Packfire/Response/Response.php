<?php

/**
 * pResponse class
 * 
 * A sugar candy for response creation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.response
 * @since 1.1-sofia
 */
class pResponse {
    
    /**
     * The format type of the response
     * @var string
     * @since 1.1-sofia
     */
    private $type;
    
    /**
     * Create a new pResponse object
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
                pload('pJsonResponse');
                $response = new pJsonResponse($object);
                break;
            case 'jsonp':
                pload('pJsonResponse');
                $response = new pJsonResponse($object, $options);
                break;
            case 'xml':
                pload('pXmlResponse');
                $response = new pXmlResponse($object);
                break;
            case 'yaml':
                pload('pYamlResponse');
                $response = new pYamlResponse($object);
                break;
            default:
                pload('pPhpSerializeResponse');
                $response = new pPhpSerializeResponse($object);
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