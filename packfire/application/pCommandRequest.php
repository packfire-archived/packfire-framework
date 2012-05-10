<?php
pload('IAppRequest');

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
     * @return pList Returns the parameters 
     * @since 1.0-sofia
     */
    public function params() {
        $result = new pList();
        $result->append($_SERVER['argv']);
        return $result;
    }
    
}