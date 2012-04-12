<?php
pload('packfire.controller.pController');
pload('packfire.scaffold.list.pScaffoldDbListView');
pload('packfire.scaffold.view.pScaffoldTableView');


/**
 * Provides functionalities to build models, entities and 
 * tables in the database.
 * 
 * Note that the pScaffolder is actually a controller which you can directly
 * use in your routing or forward requests.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.scaffold
 * @since 1.0-sofia
 */
class pScaffolder extends pController {
    
    /**
     * The database / schema to work with
     * @var pDbSchema 
     * @since 1.0-sofia
     */
    private $database;
    
    public function activate($action) {
        $this->database = $this->service('database.scaffold');
    }
    
    /**
     * Process the request and determine what to do
     * @throws pHttpException Thrown when the action is inappropriate
     * @since 1.0-sofia
     */
    public function doIndex(){
        $table = $this->params->get('use');
        if($table){
            switch($this->params->get('action')){
                case 'add':
                    $this->doAdd();
                    break;
                case 'edit':
                    $this->doEdit();
                    break;
                case 'delete':
                    $this->doDelete();
                    break;
                case 'drop':
                    $this->doDrop();
                    break;
                default:
                    $this->doView();
                    break;
            }
        }else{
            switch($this->params->get('action')){
                case 'build':
                    $this->doBuild();
                    break;
                default:
                    // no table to work with, list tables
                    $this->doList();
                    break;
            }
        }
    }
    
    /**
     * Since no table was selected, we could just list the tables
     * available in the database and show a table creation form
     * @since 1.0-sofia
     */
    public function doList(){
        $tables = $this->database->tables();
        $this->state = new pMap();
        $this->state->add('url', $this->request->url()->path());
        $this->state->add('tables', $tables);
        $this->render(new pScaffoldDbListView($this->state));
    }
    
    /**
     * Perform the creation of a new table
     * Select model or build from the scaffolder
     * @since 1.0-sofia 
     */
    public function doBuild(){
        
    }
    
    /**
     * Show the form to add a new row to the table
     * and process the addition
     * @since 1.0-sofia 
     */
    protected function doAdd(){
        
    }
    
    /**
     * Show the table information and its rows / data
     * @since 1.0-sofia 
     */
    protected function doView(){
        $table = $this->database->from($this->params['use']);
        $columns = $table->columns();
        $page = $this->params->get('page');
        $total = $table->count()->get(0);
        
        $rowPerPage = 20;
        
        $totalPages = ceil($total / $rowPerPage);
        
        if($page == null || $page < 1 || $page > $totalPages){
            $page = 1;
        }
        --$page;
        
        $rows = $table->limit($page * $rowPerPage, $rowPerPage)->fetch();
        
        $this->state['table'] = $this->params['use'];
        $this->state['columns'] = $columns;
        $this->state['rows'] = $rows;
        $this->state['page'] = $page;
        $this->state['rowPerPage'] = $rowPerPage;
        $this->state['total'] = $total;
        $this->state->add('url', $this->request->url()->path());
        $this->render(new pScaffoldTableView($this->state));
    }
    
    /**
     * Edit a single row
     * @since 1.0-sofia 
     */
    protected function doEdit(){
        
    }
    
    /**
     * Perform deletion of one row
     * @since 1.0-sofia 
     */
    protected function doDelete(){
        
    }
    
    /**
     * Removal of a table
     * @since 1.0-sofia 
     */
    protected function doDrop(){
        
    }
    
}