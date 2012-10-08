<?php
pload('ILog');
pload('packfire.io.file.IFile');
pload('packfire.template.pTemplate');

/**
 * A log file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.log
 * @since 1.0-sofia
 */
class pLog implements ILog, IFile {
    
    /**
     * The file to write to
     * @var pFile
     * @since 1.0-sofia
     */
    private $file;
    
    /**
     * The format for each log entry
     * @var string
     * @since 1.0-sofia
     */
    private $format = '{datetime}: [{context}] {message}';
    
    /**
     * Create a new pLog object
     * @param pFile|string $file The log file to write to
     * @since 1.0-sofia
     */
    public function __construct($file){
        if(is_string($file)){
            $file = new pFile($file);
        }
        $this->file = $file;
    }
    
    /**
     * Write a log entry to the log file
     * @param array|Map $data The data of the log entry
     * @since 1.0-sofia
     */
    public function write($data) {
        $template = new pTemplate($this->format);
        $template->fields()->append($data);
        $this->file->append($template->parse() . "\n");
    }
    
    /**
     * Get or set the format of writing the log entries
     * @param string $format Set the new format for the log entries to use
     * @return string Returns the format of writing the log entries
     * @since 1.0-sofia
     */
    public function format($format = null){
        if(func_num_args() == 1){
            $this->format = $format;
        }
        return $this->format;
    }
    
    /**
     * Get the pathname to the log file
     * @return string Returns the pathname of the log file.
     * @since 1.0-sofia
     */
    public function pathname(){
        return $this->file->pathname();
    }
    
}