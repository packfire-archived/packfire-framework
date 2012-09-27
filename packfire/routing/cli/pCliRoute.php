<?php
pload('packfire.routing.IRoute');

/**
 * pCliRoute class
 * 
 * A command-line interface route entry
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing.cli
 * @since 1.0-elenor
 */
class pCliRoute implements IRoute {
    
    /**
     * The name of the route
     * @var string
     * @since 1.0-elenor
     */
    private $name;
    
    /**
     * The route parameters to check
     * @var pMap
     * @since 1.0-elenor
     */
    private $params;
    
    /**
     * The name of the controller class to route to
     * @var string
     * @since 1.0-elenor
     */
    private $actual;
    
    /**
     * The parameters remapping
     * @var pMap
     * @since 1.0-elenor
     */
    private $remap;
    
    /**
     * Create a new pCliRoute object
     * @param string $name The name of the route
     * @param array|pMap $data The data retrieved from the settings
     * @since 1.0-elenor
     */
    public function __construct($name, $data) {
        $this->name = $name;
        $this->actual = $data->get('actual');
        $this->params = $data->get('params');
        $this->remap = $data->get('remap');
    }

    /**
     * Check whether the route matches the request
     * @param pCliAppRequest $request The request to check
     * @return boolean Returns true if the route matches the request, false
     *                      otherwise.
     * @since 1.0-elenor
     */
    public function match($request) {
        $ok = true;
        $requestParams = $request->params();
        $params = array();
        $this->remap($requestParams);
        if($this->params){
            foreach($this->params as $key => $param){
                if($requestParams->keyExists($key)){
                    $subject = $requestParams->get($key);
                    if(is_string($param)){
                        $ok = preg_match('`' . $param . '`is', $subject);
                    }else{
                        $ok = $subject == $param;
                    }
                }else{
                    $ok = false;
                }
                if(!$ok){
                    break;
                }
                $params[$key] = $subject;
            }
        }
        if($ok){
            $this->params = new pMap($params);
        }
        return $ok;
    }
    
    /**
     * Perform remapping of arguments
     * @param pMap $params The parameters to be remapped
     * @since 1.0-elenor
     */
    private function remap($params){
        if($this->remap){
            foreach($this->remap as $source => $target){
                if($params->keyExists($source)){
                    $value = $params->get($source);
                    $params->removeAt($source);
                    $params->add($target, $value);
                }
            }
        }
    }
    
    /**
     * The parameters in this routing
     * @var pMap
     * @since 1.0-elenor
     */
    public function params(){
        return $this->params;
    }
    
    /**
     * Get the name of the controller class to route to
     * @return string Returns the controller class name
     * @since 1.0-elenor
     */
    public function actual(){
        return $this->actual;
    }

    /**
     * Get the name of the route entry
     * @return string Returns the name
     * @since 1.0-elenor
     */
    public function name() {
        return $this->name;
    }
    
}