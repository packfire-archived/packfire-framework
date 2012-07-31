<?php
pload('packfire.routing.pRouter');

/**
 * pCliRouter class
 * 
 * A router for command line requests
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packafire.routing.cli
 * @since 1.0-elenor
 */
class pCliRouter extends pRouter {
    
    /**
     * Prepare a route with the parameters
     * @param pCliRoute $route The route to be prepared
     * @param array|pMap $params The parameters to prepare
     * @return string The final route URL
     * @since 1.0-elenor
     */
    protected function prepareRoute($route, $params) {
        $result = array();
        foreach($params as $key => $value){
            if(strlen($key) == 1){
                $key = '-' . $key;
            }else{
                $key = '--' . $key;
            }
            
            if(is_string($value)){
                $result[] = $key . ' "' . addslashes($value) . '"';
            }elseif(is_numeric($value)){
                $result[] = $key . ' ' . $value . '';
            }elseif(is_bool($value)){
                $result[] = $key;
            }
        }
        return implode(' ', $result);
    }

    /**
     * Factory manufature the route based on the configuration
     * @param string $key Name of the route
     * @param pMap $data The configuration of the route
     * @return IRoute Returns the route manufactured
     * @since 1.0-elenor
     */
    protected function routeFactory($key, $data) {
        return new pCliRoute($key, $data);
    }
    
}