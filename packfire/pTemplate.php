<?php

class pTemplate {

    const KEY_OPEN = '{';
    const KEY_CLOSE = '}';
    
    private $template;
    
    private $fields;
    
    public function __construct($template){
        $this->template = $template;
        $this->fields = new pList();
    }
    
    public function fields(){
        return $this->fields;
    }

    /**
     * Parses the template fields into the template and return the final result
     * @return string
     */
    public function parse(){
        $html = $this->template;
        foreach($this->fields as $k => $v){
            $key = self::TEMPLATE_KEY_OPEN . $k . self::TEMPLATE_KEY_CLOSE;
            if(strpos($html, $key) !== false){
                $html = str_replace($key, $v, $html);
            }
        }
        
        return $html;
    }
    
    public function __toString() {
        $this->parse();
    }
    
}
