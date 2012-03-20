<?php
pload('ILinqQuery');

/**
 * pLinqReverseQuery Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since version-created
 */
class pLinqReverseQuery implements ILinqQuery {
    
    public function run($collection) {
        return array_reverse($collection);
    }

}