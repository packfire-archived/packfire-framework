<?php

/**
 * pDbLinq Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since version-created
 */
abstract class pDbLinq implements IOrderedLinq {
    
    /**
     *
     * @var pDbConnector 
     */
    private $driver;
    
    /**
     *
     * @param pDbConnector $driver 
     */
    public function __construct($driver){
        $this->driver = $driver;
    }
    
}