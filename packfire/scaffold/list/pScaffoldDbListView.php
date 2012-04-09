<?php
pload('packfire.view.pView');
pload('packfire.template.pTemplate');

/**
 * 
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.scaffold
 * @since 1.0-sofia
 */
class pScaffoldDbListView extends pView {
    
    protected function create() {
        $this->template(new pTemplate(file_get_contents()))
    }
    
}