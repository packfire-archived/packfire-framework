<?php
namespace Packfire\Test\Mocks;

use Packfire\View\View as CoreView;
use Packfire\Test\Mocks\SampleModel;
use Packfire\Model\ObjectObserver;

/**
 * View class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Test\Mocks
 * @since 1.0-sofia
 */
class View extends CoreView {

    public $title;

    public function using($template){
        $this->template($template);
    }

    protected function create() {
        foreach($this->state as $key => $value){
            $this->define($key, $value);
            $this->filter($key, 'trim');
        }
        $object = new ObjectObserver($this);
        $this->bind('binder', $object, 'title');
        $this->title = 'test2';
        $this->define('route', $this->route('home'));
    }

}