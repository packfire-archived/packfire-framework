<?php
pload('packfire.view.pView');
pload('packfire.template.moustache.pMoustacheTemplate');

/**
 * Welcome view for Setup
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.setup
 * @since 1.0-sofia
 */
class pSetupWelcomeView extends pView {

    protected function create() {
        $file = dirname(__FILE__) . '/../template/welcome.html';
        $this->template(new pMoustacheTemplate(file_get_contents($file)));
        $this->define('version', __PACKFIRE_VERSION__);
    }
    
}