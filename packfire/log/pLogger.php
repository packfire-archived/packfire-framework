<?php
pload('ILogger');
pload('pLog');
pload('packfire.io.file.pPath');
pload('packfire.io.file.pFile');
pload('packfire.datetime.pDateTime');

/**
 * Logger Service
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.log
 * @since 1.0-sofia
 */
class pLogger implements ILogger {
    
    /**
     * The log storage to write to
     * @var ILog
     * @since 1.0-sofia
     */
    private $log;
    
    /**
     * Create a new Logger
     * @param string|IFile|ILog $log
     * @since 1.0-sofia
     */
    public function __construct($log, $context = null){
        if(is_string($log)){
            $log = self::logFactory($log);
        }
        $this->log = $log;
        $this->context = $context;
    }
    
    /**
     * Write a log message
     * @param string $message The message to write
     * @param string $context (optional) The context of this log entry
     * @since 1.0-sofia
     */
    public function log($message, $context = null){
        $now = pDateTime::now();
        $data = array(
            'message' => $message,
            'datetime' => $now->toISO8601(),
            'date' => $now->format('j-M-Y'),
            'time' => $now->format('h:i:s a'),
            'context' => $context
        );
        $this->log->write($data);
    }
    
    /**
     * Create ILog based on file
     * @param string|pFile $file The file to create the object
     * @return ILog
     * @since 1.0-sofia
     */
    public static function logFactory($file){
        if($file instanceof pFile){
            $file = $file->pathname();
        }
        $extension = pPath::extension($file);
        $log = null;
        switch($extension){
            case 'log':
                $log = new pLog($file);
                break;
        }
        return $log;
    }
    
}