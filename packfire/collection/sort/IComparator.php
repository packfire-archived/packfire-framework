<?php

/**
 * An interface that allows comparing.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire/collection/sort
 * @since 1.0-sofia
 */
interface IComparator {
    
    public function compare($a, $b);
    
}