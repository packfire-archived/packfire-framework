<?php
pload('IDebugOutput');

/**
 * pConsoleDebugOutput Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger
 * @since 1.0-sofia
 */
class pConsoleDebugOutput implements IDebugOutput {
    
    /**
     *
     * @var pList
     */
    private $buffer;
    
    /**
     *
     * @var pList
     */
    private $types;
    
    public function __construct(){
        $this->buffer = new pList();
        $this->types = new pList();
    }

    public function write($message, $value = null, $type = 'log') {
        if(func_num_args() == 1){
            $this->buffer->add(sprintf('<div class="pfLine ' . $type . '">' .
                    '<div class="pfMessage">%s</div><div class="pfClear"></div></div>',
                    $message));
        }else{
            $this->buffer->add(
                sprintf('<div class="pfLine ' . $type . '"><div class="pfMessage">%s</div>' .
                        '<div class="pfValue">%s</div><div class="pfClear"></div></div>',
                        $message, $value));
        }
        if(!$this->types->contains($type)){
            $this->types->add($type);
        }
    }
    
    public function output(){
        echo '<script type="text/javascript"></script>';
        echo '<style type="text/css">
            #pfDebuggerConsole{
                position:absolute; 
                bottom: 0px; 
                width:80%;
                border:1px solid #000;
                background:#FFF;
                left:0; right:0;
                margin:0px auto;
            }
            #pfDebuggerConsole .pfLine{
                border-bottom: 1px solid #CCC;
                padding:10px;
            }
            #pfDebuggerConsole .pfClear{
                clear:both;
            }
            #pfDebuggerConsole .pfMessage{
                float:left;
            }
            #pfDebuggerConsole .pfValue{
                float:right;
            }
            </style>';
        echo '<div id="pfDebuggerConsole">';
        foreach($this->buffer as $line){
            echo $line;
        }
        echo '</div>';
    }
    
}