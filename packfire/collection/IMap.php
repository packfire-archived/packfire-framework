<?php
pload('IList');

/**
 * A Hash Map interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire/collection
 * @since 1.0-sofia
 */
interface IMap extends IList {
    
    public function keyExists($key);
    
    public function keys();
    
    public function values();
    
}