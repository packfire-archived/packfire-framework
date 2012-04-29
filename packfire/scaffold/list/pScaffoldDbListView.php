<?php
pload('packfire.view.pView');
pload('packfire.template.pTemplate');
pload('packfire.text.pInflector');
pload('packfire.net.http.pUrl');

/**
 * Database List View for Scaffold
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.scaffold.list
 * @since 1.0-sofia
 */
class pScaffoldDbListView extends pView {
    
    private $state;
    
    public function __construct($state){
        parent::__construct();
        $this->state = $state;
    }
    
    protected function create() {
        $this->template(new pTemplate(
                file_get_contents(pPath::path(__FILE__) . '/viewTemplate.html')
            ));
        $count = count($this->state['tables']);
        $countDisplay = $count . ' ' . pInflector::quantify($count, 'table');
        $this->define('title', 'Packfire Scaffolding - '
                . $countDisplay);
        $this->define('countDisplay', $countDisplay);
        $list = '';
        if($count > 0){
            foreach($this->state['tables'] as $table){
                $list .= '<div><a href="' . $this->state['url'] . '?use=' 
                        . pUrl::encode($table) . '">' . $table . '</a></div>';
            }
        }else{
            $list = '<div class="message">No tables found in this schema.</div>';
        }
        $this->define(array(
            'list' => $list,
            'url' => $this->state['url']
        ));
    }
    
}