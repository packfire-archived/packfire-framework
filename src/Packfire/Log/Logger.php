<?php
namespace Packfire\Log;

use Packfire\Linq\Query\ILogger;
use Packfire\Linq\Query\Log;
use Packfire\IO\File\Path;
use Packfire\IO\File\File;
use Packfire\DateTime\DateTime;

/**
 * Logger class
 * 
 * Logger Service
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Log
 * @since 1.0-sofia
 */
class Logger implements ILogger {
    
    /**
     * The log storage to write to
     * @var ILog
     * @since 1.0-sofia
     */
    private $log;
    
    /**
     * Create a new Logger object
     * @param string|IFile|ILog $log The log file to write to
     * @since 1.0-sofia
     */
    public function __construct($log){
        if(is_string($log)){
            $log = self::logFactory($log);
        }
        $this->log = $log;
    }
    
    /**
     * Write a log message
     * @param string $message The message to write
     * @param string $context (optional) The context of this log entry
     * @since 1.0-sofia
     */
    public function log($message, $context = null){
        $now = DateTime::now();
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
     * @param string|File $file The file to create the object
     * @return ILog
     * @since 1.0-sofia
     */
    public static function logFactory($file){
        if($file instanceof File){
            $file = $file->pathname();
        }
        $extension = Path::extension($file);
        $log = null;
        switch($extension){
            case 'log':
                $log = new Log($file);
                break;
        }
        return $log;
    }
    
}