<?php
pload('packfire.application.IAppRequest');
pload('pCommandParser');

/**
 * pCliAppRequest class
 * 
 * A request made via the command line
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.cli
 * @since 1.0-sofia
 */
class pCliAppRequest implements IAppRequest {
    
    /**
     * The parameters of the command request
     * @var pMap
     * @since 1.0-elenor
     */
    private $params;
    
    /**
     * Create a new pCliAppRequest object
     * @param array $arguments (optional) The array of arguments for the request.
     *           If not set, the arguments will be loaded from $_SERVER['argv'].
     * @since 1.0-elenor
     */
    public function __construct($arguments = null){
        if(!$arguments){
            $arguments = $_SERVER['argv'];
        }
        $parser = new pCommandParser($arguments);
        $this->params = $parser->result();
    }
    
    /**
     * Get the CLI arguments from the command parser
     * @return pMap Returns the parameters 
     * @since 1.0-sofia
     */
    public function params() {
        return $this->params;
    }
    
}