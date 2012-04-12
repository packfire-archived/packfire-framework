<?php
pload('packfire.view.pView');
pload('packfire.template.pTemplate');
pload('packfire.text.pInflector');
pload('packfire.net.http.pUrl');

/**
 * Table View for Scaffold
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.scaffold.view
 * @since 1.0-sofia
 */
class pScaffoldTableView extends pView {
    
    private $state;
    
    public function __construct($state){
        parent::__construct();
        $this->state = $state;
    }
    
    protected function create() {
        $this->template(new pTemplate(
                file_get_contents(pPath::path(__FILE__) . '/viewTemplate.html')
            ));
        extract($this->state->toArray());
        $tableDisplay = $table . ' (' . $total . ')';
        $dataTable = '';
        if($total == 0){
            $dataTable = '<div class="message">No data found in table.</div>';
        }else{
            $dataTable = '<table>';
            $dataTable .= '<tr>';
            foreach($columns as $idx => $column){
                $dataTable .= '<th>' . $column->name() . '</th>';
            }
            $dataTable .= '<th>' . 'Options' . '</th>';
            $dataTable .= '</tr>';
            foreach($rows as $row){
                $dataTable .= '<tr>';
                foreach($columns as $idx => $column){
                    $dataTable .= '<td>' . $row[$idx] . '</td>';
                }
                $dataTable .= '<td>' . 'Edit Delete' . '</td>';
                $dataTable .= '</tr>';
            }
            $dataTable .= '</table>';
        }
        $this->define(array(
            'title' => 'Packfire Scaffolding - ' . $tableDisplay,
            'dataTable' => $dataTable,
            'rowCount' => $total,
            'tableName' => $tableDisplay,
            'url' => $url
        ));
    }
    
}