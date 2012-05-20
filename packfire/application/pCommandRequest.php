<?php
pload('IAppRequest');
pload('pCommandParser');

/**
 * A request made via the command line
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
class pCommandRequest implements IAppRequest {
    
    /**
     * Get the CLI arguments
     * @return pMap Returns the parameters 
     * @since 1.0-sofia
     */
    public function params() {
        $parser = new pCommandParser(implode(' ', $_SERVER['argv']));
        return $parser->result();
    }
    
}