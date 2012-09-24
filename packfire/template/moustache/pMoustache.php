<?php
pload('packfire.collection.pList');

/**
 * pMoustache class
 * 
 * A PHP implementation of Mustache,
 * a simple logic-less templating system
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.template.moustache
 * @since 1.0-sofia
 */
class pMoustache {
    
    /**
     * The tag regular expression 
     * @since 1.0-sofia
     */
    const TAG_REGEX = '`(([{]{2})([\^&#/{\!\>\<]{0,1})(%s)([}]{2}))`is';
    
    const TYPE_NORMAL = '';
    const TYPE_OPEN = '#';
    const TYPE_CLOSE = '/';
    const TYPE_UNESCAPE = '&';
    const TYPE_UNESCAPETRIPLE = '{';
    const TYPE_INVERT = '^';
    const TYPE_COMMENT = '!';
    const TYPE_PARTIAL1 = '>';
    const TYPE_PARTIAL2 = '<';
    
    /**
     * The output buffer string
     * @var string
     * @since 1.0-sofia
     */
    private $buffer;
    
    /**
     * The template to be parsed
     * @var string
     * @since 1.0-sofia
     */
    private $template;
    
    /**
     * The parameters to work with
     * @var mixed
     * @since 1.0-sofia
     */
    private $parameters;
    
    /**
     * The partials to be included
     * @var array|pMap
     * @since 1.0-sofia
     */
    private $partials;
    
    /**
     * The escaper callback
     * @var Closure|callback
     * @since 1.0-sofia
     */
    private $escaper;
    
    /**
     * Create a new pMoustache object
     * @param string $template (optional) Set the template to render
     * @param mixed $parameters (optional) The parameters to render the template
     * @param array|pMap $partials (optional) The partials to supply to the 
     *                  template
     * @param Closure|callback $escaper (optional) The escaper function to use
     *                  to escape the properties
     * @since 1.0-sofia
     */
    public function __construct($template = null, $parameters = null,
            $partials = null, $escaper = null){
        $this->template = $template;
        $this->parameters = $parameters;
        $this->partials = $partials;
        $this->escaper = $escaper;
    }
    
    /**
     * Escape the text using the defined $escaper or the default
     * htmlspecialchars() function.
     * @param string $text The text to be escaped
     * @return string The escaped text.
     * @since 1.0-sofia
     */
    protected function escape($text){
        if(is_callable($this->escaper)){
            return call_user_func($this->escaper, $text);
        }else{
            return htmlspecialchars($text, ENT_COMPAT, 'UTF-8'); 
        }
    }
    
    /**
     * Perform parsing of a scope
     * @param mixed $scope The parameter scope to work with
     * @param integer $start The start position of the template string
     *              to start working from
     * @param integer $end The end position of the template string
     *              to stop working at
     * @since 1.0-sofia
     */
    private function parse($scope, $start, $end){
        if($scope instanceof pList){
            $scope = $scope->toArray();
        }
        if($this->isArrayOfObjects($scope)){
            foreach($scope as $item){
                $this->parse($item, $start, $end);
            }
        }else{
            $position = $start;
            $templateScope = substr($this->template, $start, $end - $start);
            while($position < $end){
                $match = array();
                $i = preg_match($this->buildMatchingTag(), $templateScope,
                        $match, PREG_OFFSET_CAPTURE, $position - $start);
                if($i){
                    $tagLength = strlen($match[0][0]);
                    $tagStart = $match[0][1];
                    $tagEnd = $tagStart + $tagLength;
                    $name = trim($match[5][0]);
                    $tagType = $match[3][0];
                    $this->buffer .= substr($this->template, $position,
                            $tagStart + $start - $position);
                    switch($tagType){
                        case self::TYPE_COMMENT:
                            // comment, do nothing
                            $position = $start + $tagEnd;
                            break;
                        case self::TYPE_OPEN:
                            $position = $start + $tagEnd;
                            $this->findClosingTag($name, $position, $end);
                            $property = $this->getProperty($scope, $name);
                            if($property !== false && $property !== null){
                                if(is_scalar($property)){
                                    $property = $scope;
                                }
                                $this->parse($property, $start + $tagEnd,
                                        $position);
                            }
                            $position = $position + $tagLength;
                            break;
                        case self::TYPE_INVERT:
                            $position = $start + $tagEnd;
                            $this->findClosingTag($name, $position, $end);
                            $property = $this->getProperty($scope, $name);
                            if($property === false || $property === null){
                                if(is_scalar($property)){
                                    $property = $scope;
                                }
                                $this->parse($property, $tagEnd,
                                        $position);
                            }
                            $position = $position + $tagLength;
                            break;
                        case self::TYPE_PARTIAL1:
                        case self::TYPE_PARTIAL2:
                            $this->partial($name);
                            $position = $start + $tagEnd;
                            break;
                        case self::TYPE_UNESCAPETRIPLE:
                        case self::TYPE_UNESCAPE:
                            $this->addToBuffer($scope, $name, false);
                            $position = $start + $tagEnd;
                            break;
                        default:
                            $this->addToBuffer($scope, $name);
                            $position = $start + $tagEnd;
                            break;
                    }
                }else{
                    // no more found
                    $this->buffer .= substr($this->template, $position,
                            $end - $position);
                    $position = $end;
                }
            }
        }
    }
    
    /**
     * Get the property from the scope.
     * Note that if property is not found in the scope, the
     * method will look from the parent-most scope.
     * @param mixed $scope The scope to get the property from
     * @param string $name Name of the property
     * @return mixed Returns the property fetched.
     * @since 1.0-sofia
     */
    private function getProperty($scope, $name){
        $result = null;
        $higherPower = false;
        if(is_object($scope)){
            if(property_exists($scope, $name)){
                $result = $scope->$name;
            }elseif(is_callable($scope, $name)){
                $result = $scope->$name();
            }elseif($scope !== $this->parameters){
                $higherPower = true;
            }
        }elseif(is_array($scope)){
            if(array_key_exists($name, $scope)){
                $result = $scope[$name];            
            }elseif($scope !== $this->parameters){
                $higherPower = true;
            }
        }elseif($scope !== $this->parameters){
            $higherPower = true;
        }
        if($higherPower){
            $result = $this->getProperty($this->parameters, $name);
        }
        return $result;
    }
    
    /**
     * Add a property to the buffer and determine if it should be escaped
     * @param mixed $scope The scope of to get the property from
     * @param mixed $name The name of the property
     * @param boolean $escape (optional) Set whether to escape the property. 
     *                 Set this to true for escaping, and false otherwise.
     *                 Defaults to true.
     * @since 1.0-sofia
     */
    private function addToBuffer($scope, $name, $escape = true){
        $result = $this->getProperty($scope, $name);
        if(is_array($result)){
            $result = implode('', $result);
        }
        if($escape){
            $result = $this->escape($result);
        }
        $this->buffer .= $result;
    }
    
    /**
     * Get the partial by name and add to the buffer
     * @param string $name Name of the partial
     * @since 1.0-sofia
     */
    protected function partial($name){
        if(is_array($this->partials) 
                && array_key_exists($name, $this->partials)){
            $partial = $this->partials[$name];
            $partialMoustache = new pMoustache($partial,
                    $this->parameters, 
                    $this->partials, 
                    $this->escaper);
            $partialResult = $partialMoustache->render();
            $this->buffer .= $partialResult;
        }
    }
    
    /**
     * Check if the scope is an array of objects
     * @param mixed $scope The scope to be checked
     * @return boolean Returns true if the scope is an array of objects,
     *                  false otherwise.
     * @since 1.0-sofia
     */
    private function isArrayOfObjects($scope){
        return is_array($scope) && (($this->parameters != $scope && $scope === array())
                || (array_key_exists(0, $scope)
                && array_key_exists(count($scope) - 1, $scope)
                && (is_array($scope[0]) || is_object($scope[0]))));
    }
    
    /**
     * Find the closing tag and shift the position variable to the front
     * of the closing tag.
     * @param string $name The name of the closing tag to find
     * @param string $position The position to be set to
     * @param string $end The end of the tempalte scope
     * @since 1.0-sofia
     */
    private function findClosingTag($name, &$position, $end){
        $nest = 0;
        $templateScope = substr($this->template, $position, $end - $position);
        $start = $position;
        $notDone = true;
        while($position < $end && $notDone){
            $match = array();
            $i = preg_match($this->buildMatchingTag($name), $templateScope,
                     $match, PREG_OFFSET_CAPTURE, $position - $start);
            if($i){
                $tagLength = strlen($match[0][0]);
                $tagEnd = $match[0][1] + $tagLength;
                $tagType = $match[3][0];
                switch($tagType){
                    case self::TYPE_INVERT:
                    case self::TYPE_OPEN:
                        ++$nest;
                        $position = $start + $tagEnd;
                        break;
                    case self::TYPE_CLOSE:
                        if($nest == 0){
                            $position = $start + $match[0][1];
                            $notDone = false;
                        }elseif($nest > 0){
                            $position = $start + $tagEnd;
                            --$nest;
                        }
                        break;
                    default:
                        $position = $start + $tagEnd;
                        break;
                }
            }else{
                $position = $end;
                $notDone = false;
                break;
            }
        }
        
    }
    
    /**
     * Set the template to be rendered by Moustache
     * @param string $template The template to render
     * @return pMoustache Returns self for chaining
     * @since 1.0-sofia
     */
    public function template($template){
        $this->template = $template;
        return $this;
    }
    
    /**
     * Set the parameters to be rendered into the template
     * @param mixed $parameters The parameters
     * @return pMoustache Returns self for chaining
     * @since 1.0-sofia
     */
    public function parameters($parameters){
        $this->parameters = $parameters;
        return $this;
    }
    
    /**
     * Render the Moustache template
     * @return string Returns the parsed template
     * @since 1.0-sofia 
     */
    public function render(){
        if(get_class($this) != __CLASS__){
            $this->parameters = $this;
        }
        if($this->parameters instanceof pList){
            $this->parameters = $this->parameters->toArray();
        }
        $this->buffer = '';
        $this->parse($this->parameters, 0, strlen($this->template));
        return $this->buffer;
    }
    
    /**
     * Build the tag matching regular expression
     * @param string $name (optional) The tag name to match
     * @return string Returns the final regular expression
     * @since 1.0-sofia
     */
    private function buildMatchingTag($name = '([^}].+?)([}]{0,1})'){
        return sprintf(self::TAG_REGEX, $name);
    }
    
}