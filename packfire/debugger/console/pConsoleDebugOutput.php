<?php
pload('packfire.io.file.pPath');
pload('packfire.debugger.IDebugOutput');

/**
 * Provides Client-side GUI debugging console output
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger.console
 * @since 1.0-sofia
 */
class pConsoleDebugOutput implements IDebugOutput {
    
    /**
     * The output buffer
     * @var pList
     * since 1.0-sofia
     */
    private $buffer;
    
    /**
     * The types of log that has been entered
     * @var pMap
     * @since 1.0-sofia
     */
    private $types;
    
    /**
     * Create a new pConsoleDebugOutput
     * @since 1.0-sofia 
     */
    public function __construct(){
        $this->buffer = new pList();
        $this->types = new pMap();
    }

    /**
     * Write the log message to the console
     * @param string $message The message to write to the console
     * @param string $value (optional) The secondary value to the message
     * @param string $type (optional) The type of message written, 
     *              defaults to 'log'.
     * @since 1.0-sofia
     */
    public function write($message, $value = null, $type = 'log') {
        if($type == 'dump'){
            $message = '<pre>' . $message . '</pre>';
        }
        if(func_num_args() == 1){
            $this->buffer->add(sprintf('<div class="pfLine %s">' .
                    '<div class="pfMessage"><b>%s</b>: %s</div><div class="pfClear"></div></div>',
                    $type, $type, $message));
        }else{
            $this->buffer->add(
                sprintf('<div class="pfLine %s"><div class="pfMessage"><b>%s</b>: %s</div>' .
                        '<div class="pfValue">%s</div><div class="pfClear"></div></div>',
                        $type, $type, $message, $value));
        }
        if(!$this->types->keyExists($type)){
            $this->types->add($type, 0);
        }
        $this->types->add($type, $this->types[$type] + 1);
    }
    
    /**
     * Output the console
     * @since 1.0-sofia 
     */
    public function output(){
        $template = new pTemplate(file_get_contents(pPath::path(__FILE__) 
                . '/template.html'));
        
        $lines = '';
        foreach($this->buffer as $line){
            $lines .= $line;
        }
        $template->fields()->add('lines', $lines);
        
        $tabs = '';
        foreach($this->types as $type => $count){
            $tabs .= '<a href="#debugger-' . $type 
                    . '" onclick="PfConsoleDebugger.showByTab(\'' 
                    . $type . '\');return false;">' 
                    . $type . ' (' . $count . ')</a>';
        }
        $template->fields()->add('tabs', $tabs);
        
        $template->fields()->add('totalCount', $this->buffer->count());
        
        echo $template->parse();   
    }
    
}