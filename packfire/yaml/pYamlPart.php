<?php

/**
 * pYamlPart Description
 *
 * @author Sam Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since version-created
 */
class pYamlPart {
    
    const DOC_START = '---';
    const DOC_END = '...';
    
    const KEY_VALUE_SEPARATOR = ':';
    
    const INDENTATION = '    ';
    
    const SEQUENCE_ITEM_BULLET = '- ';
    
    const MULTILINE_SEQUENCE_OF_MAP_START = '`\s*\- \s*([^\s\:\{].*)\s*\:\s*([^\s].*)\s*`ism';
    
    public static function quotationMarkers(){
        return array('"', '\'');
    }
    
}