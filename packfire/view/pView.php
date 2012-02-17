<?php
pload('IView');
pload('packfire.collection.pMap');
pload('packfire.template.pTemplate');

/**
 * The generic view class.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.view
 * @since 1.0-sofia
 */
abstract class pView implements IView {
    
    /**
     * The fields in the view defined
     * @var pMap
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * The template for the view to render
     * @var pTemplate 
     * @since 1.0-sofia
     */
    private $template;
    
    /**
     * The template for the view to render
     * @var pTheme 
     * @since 1.0-sofia
     */
    private $theme;
    
    /**
     * Create a new view 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->fields = new pMap();
    }
    
    /**
     * Define a template field to populate.
     * @param string $key Name of the field
     * @param mixed $value (optional) Set the template field value
     * @return mixed Returns the current value set at $key if $value is not set.
     * @since 1.0-sofia
     */
    protected function define($key, $value = null){
        if(func_num_args() == 1){
            return $this->fields[$key];
        }else{
            $this->fields[$key] = $value;
        }
    }
    
    /**
     * Set the template used by the view
     * @param pTemplate $template (optional) The template to use
     * @return pTemplate Returns an instance of self for chaining.
     * @since 1.0-sofia
     */
    protected function template($template){
        $this->template = $template;
        return $this;
    }
    
    /**
     * Set the theme used by the view
     * @param pTheme $theme The theme to use
     * @return pView Returns an instance of self for chaining.
     * @since 1.0-sofia
     */
    protected function theme($theme){
        $this->theme = $theme;
        return $this;
    }
    
    /**
     * Prepare and create the view fields
     * @since 1.0-sofia
     */
    protected abstract function create();
    
    /**
     * Get the output of the view.
     * @return string Returns the output of this view.
     * @since 1.0-sofia
     */
    public function render(){
        $output = '';
        ob_start();
        $this->create();
        $output = ob_get_contents();
        ob_end_clean();
        if($this->theme){
            // render the theme
            $this->theme->render();
            // forward the theme fields to the view
            foreach($this->theme->fields() as $key => $value){
                $this->define('template.' . $key, $value);
            }
        }
        
        if($this->template){
            $this->template->fields()->append($this->fields);
            if($output){
                $this->template->fields()->add('view.output', $output);
            }
            $output = $this->template->parse();
        }
        return $output;
    }
    
}