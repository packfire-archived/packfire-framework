<?php

/**
 * Provides operations on template parsing.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pTemplate {

    /**
     * The template tag opening key 
     * @since 1.0-sofia
     */
    const KEY_OPEN = '{';
    
    /**
     * The template tag closing key 
     * @since 1.0-sofia
     */
    const KEY_CLOSE = '}';
    
    /**
     * The template containing the tags
     * @var string
     * @since 1.0-sofia
     */
    private $template;
    
    /**
     * The template fields
     * @var pMap
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * Create a new template
     * @param string $template The template to use
     */
    public function __construct($template){
        $this->template = $template;
        $this->fields = new pMap();
    }
    
    /**
     * Get the template fields
     * @return pMap Returns the template fields hash map
     * @since 1.0-sofia
     */
    public function fields(){
        return $this->fields;
    }

    /**
     * Parses the template fields into the template and return the final result
     * @return string
     * @since 1.0-sofia
     */
    public function parse(){
        $html = $this->template;
        foreach($this->fields as $k => $v){
            $key = self::KEY_OPEN . $k . self::KEY_CLOSE;
            if(strpos($html, $key) !== false){
                $html = str_replace($key, $v, $html);
            }
        }
        
        return $html;
    }
    
}
