<?php
namespace Packfire\Database;

use Packfire\Linq\ILinq as ICoreLinq;

/**
 * ILinq interface
 * 
 * An abstract database LINQ interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
interface ILinq extends ICoreLinq {
    
    public function model($model);
    
}