<?php
pload('ILog');
pload('packfire.io.file.IFile');
pload('packfire.template.pTemplate');

/**
 * A log file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.log
 * @since 1.0-sofia
 */
class pLog implements ILog, IFile {
    
    /**
     * The file to write to
     * @var pFile
     */
    private $file;
    
    /**
     * The format for each log entry
     * @var string
     */
    private $format = '{datetime}: [{context}] {message}';
    
    public function __construct($file){
        if(is_string($file)){
            $file = new pFile($file);
        }
        $this->file = $file;
    }
    
    public function write($data) {
        $template = new pTemplate($this->format);
        $template->fields()->append($data);
        var_dump($template);
        $this->file->append($template->parse() . "\n");
    }
    
    public function format($format = null){
        
    }
    
    public function pathname(){
        return $this->file->pathname();
    }
    
}