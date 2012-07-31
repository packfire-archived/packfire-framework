<?php
pload('packfire.routing.IRoute');

/**
 * pCliRoute class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing.cli
 * @since 1.0-elenor
 */
class pCliRoute implements IRoute {
    
    private $name;
    
    private $params;
    
    /**
     * Create a new pCliRoute object
     * @param string $name The name of the route
     * @param array|pMap $data The data retrieved from the settings
     * @since 1.0-elenor
     */
    public function __construct($name, $data) {
        $this->name = $name;
        $this->params = $data;
    }

    public function match($request) {
        
    }

    public function name() {
        return $this->name;
    }
    
}