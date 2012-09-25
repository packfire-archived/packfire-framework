<?php
pload('packfire.io.file.pFile');

/**
 * pMoustacheFile class
 * 
 * A moustache template file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.template.moustache
 * @since 1.1-sofia
 */
class pMoustacheFile extends pMoustacheTemplate {
    
    /**
     * Create a new pMoustacheFile object
     * @param pFile|string $file The file or pathname to the file
     * @since 1.1-sofia
     */
    public function __construct($file) {
        if($file instanceof pFile){
            $file = $file->pathname();
        }
        parent::__construct(file_get_contents($file));
    }
    
}