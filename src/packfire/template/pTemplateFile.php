<?php

/**
 * pTemplateFile Description
 *
 * @author Sam Yong
 * @copyright Copyright (c) 2012, Sam Yong
 * @license Expression license is undefined on line 8, column 15 in Templates/Scripting/Packfire Framework/PackfireClass.php.
 * @package package
 * @since version-created
 */
class pTemplateFile extends pTemplate implements ITemplateFile {
    
    /**
     * Create a new pTemplateFile object
     * @param pFile|string $file The file or pathname to the file
     * @since 1.1-sofia
     */
    public function __construct($file){
        if($file instanceof pFile){
            $file = $file->pathname();
        }
        parent::__construct(file_get_contents($file));
    }
    
}