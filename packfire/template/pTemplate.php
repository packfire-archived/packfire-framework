<?php
pload('ITemplate');
pload('packfire.collection.pMap');
pload('packfire.text.regex.pRegex');

/**
 * Provides operations on template parsing.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.template
 * @since 1.0-sofia
 */
class pTemplate implements ITemplate {

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
     * @since 1.0-sofia
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
        $result = $this->template;
        foreach($this->fields as $key => $v){
            $key = self::KEY_OPEN . $key . self::KEY_CLOSE;
            if(strpos($result, $key) !== false){
                $result = str_replace($key, $v, $result);
            }
        }
        
        return $result;
    }

    /**
     * Get the list of tokens found in the template
     * @return pList Returns the list of tokens
     * @since 1.0-sofia
     */
    public function tokens(){
        $tokens = new pList();
        $matches = array();
        $i = preg_match_all('`' . pRegex::escape(self::KEY_OPEN) .
                '([a-zA-Z0-9\.]+)' . pRegex::escape(self::KEY_CLOSE) .
                '`is', $this->template, $matches, PREG_SET_ORDER);
        if($i > 0){
            foreach($matches as $m){
                $tokens->add($m[1]);
            }
        }
        return $tokens;
    }
    
}
