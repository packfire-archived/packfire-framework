<?php

/**
 * File abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io.file
 * @since 1.0-sofia
 */
interface IFile {
    
    /**
     * Get the pathname to the file
     * @return string Returns the pathname to the file
     * @since 1.0-sofia
     */
    public function pathname();
    
}