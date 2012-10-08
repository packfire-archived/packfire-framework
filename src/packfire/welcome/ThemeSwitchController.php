<?php
namespace Packfire\Welcome;

use Packfire\Application\Pack\Controller;

/**
 * ThemeSwitchController class
 * 
 * Switches between themes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.welcome
 * @since 1.0-sofia
 */
class ThemeSwitchController extends Controller {
    
    public function index($theme){
        $this->service('session')->set('theme', $theme);
        $this->redirect($this->route('home'));
    }
    
}