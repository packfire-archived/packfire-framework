<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Collection;

/**
 * Helper class for array functionalities
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class ArrayHelper {
    
    /**
     * Distinctively perform array_merge_recursive(). In array_merge_recursive(), 
     * duplicates are made into an array of two or more values. 
     * 
     * @param array $array1 The array to set the defaults
     * @param array $array2,... The subsequent arrays to merge recursively and
     *              distinctively.
     * @return array Returns the resulting array
     * @since 1.0-sofia
     */
    public static function mergeRecursiveDistinct($array1, $array2){
        $merged = $array1;
        if(func_num_args() > 2){
            $args = func_get_args();
            array_shift($args);
            foreach($args as $arr){
                $merged = self::mergeRecursiveDistinct($merged, $arr);
            }
        }else{
            foreach($array2 as $key => $value){
                if(is_array($value) && isset($merged[$key])
                        && is_array($merged[$key])){
                    $merged[$key] = self::mergeRecursiveDistinct(
                                $merged[$key],
                                $value
                            );
                }else{
                    $merged[$key] = $value;
                }
            }
        }
        return $merged;
    }
    
}