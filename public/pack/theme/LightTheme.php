<?php

/**
 * Light Theme
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package candice.theme
 * @since 1.0-sofia
 */
class LightTheme extends AppTheme {
    
    public function render() {
        $this->fields()->add('style', 'light');
    }
    
}