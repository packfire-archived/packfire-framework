<?php

/**
 * Contains constants that identify parts of the document
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.yaml
 * @since 1.0-sofia
 */
class pYamlPart {
    
    /**
     * Start of the document, three hypens 
     * @since 1.0-sofia
     */
    const DOC_START = '---';
    
    /**
     * End of the document, three periods
     * @since 1.0-sofia
     */
    const DOC_END = '...';
    
    /**
     * Key value separator
     * @since 1.0-sofia 
     */
    const KEY_VALUE_SEPARATOR = ':';
    
    /**
     * Sequence Item Bullet
     * @since 1.0-sofia 
     */
    const SEQUENCE_ITEM_BULLET = '- ';
    
    /**
     * Sequence Item Bullet on an Empty Line
     * @since 1.0-sofia 
     */
    const SEQUENCE_ITEM_BULLET_EMPTYLINE = "-\n";
    
    /**
     * Quotation markers
     * @return array Returns an array of quote markers
     * @since 1.0-sofia 
     */
    public static function quotationMarkers(){
        return array('"', '\'');
    }
    
}