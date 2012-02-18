<?php
pload('packfire.collection.pMap');

/**
 * A Theme...
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.view
 * @since 1.0-sofia
 */
abstract class pTheme {
   
    /**
     * The fields in the theme settings defined
     * @var pMap
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * Create a new pTheme object
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->fields = new pMap();
    }
    
    /**
     * Get the theme settings defined
     * @return pMap Returns a map of theme settings
     */
    public function fields(){
        return $this->fields;
    }
    
    /**
     * Prepare and render the theme settings
     * @since 1.0-sofia
     */
    public abstract function render();
    
}