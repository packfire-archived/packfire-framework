<?php

if(!interface_exists('JsonSerializable')){

    /**
    * JsonSerializable interface
    *
    * @author Sam-Mauris Yong / mauris@hotmail.sg
    * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
    * @license http://www.opensource.org/licenses/bsd-license New BSD License
    * @package packfire.data.serialization
    * @since 1.0-sofia
    */
    interface JsonSerializable {

        public function jsonSerialize();

    }

}