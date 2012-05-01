<?php
pload('packfire.plinq.ILinq');

/**
 * An abstract database LINQ interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
interface IDbLinq extends ILinq {
    
    public function model($model);
    
}