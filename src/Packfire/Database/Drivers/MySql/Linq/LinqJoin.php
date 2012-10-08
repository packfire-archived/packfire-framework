<?php
namespace Packfire\Database\Drivers\MySql\Linq;

use ILinqQuery;
use Packfire\Text\Regex\Regex;

/**
 * LinqJoin class
 * 
 * A MySQL LINQ Join statement
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql\Linq
 * @since 1.0-sofia
 */
class LinqJoin implements ILinqQuery {
    
    /**
     * The target table
     * @var string
     * @since 1.0-sofia
     */
    private $target;
    
    /**
     * The outer table to join
     * @var string
     * @since 1.0-sofia
     */
    private $source;
    
    /**
     * The key in the inner table
     * @var string
     * @since 1.0-sofia
     */
    private $innerKey;
    
    /**
     * The key in the outer table
     * @var string
     * @since 1.0-sofia
     */
    private $outerKey;
    
    /**
     * The selector
     * @var string
     * @since 1.0-sofia
     */
    private $selector;
    
    /**
     * Create a new LinqJoin object
     * @param string $target The table targeted
     * @param string $source The table to join
     * @param string $innerKey The inner key
     * @param string $outerKey The outer key
     * @param string $selector The selector 
     * @since 1.0-sofia
     */
    public function __construct($target, $source, $innerKey, $outerKey, $selector){
        $this->source = $source;
        $this->target = $target;
        $this->innerKey = $innerKey;
        $this->outerKey = $outerKey;
        $this->selector = $selector;
    }
    
    /**
     * Create and get the statement
     * @return string Returns the resulting statement
     * @since 1.0-sofia
     */
    public function create(){
        $regex = new Regex('`^\s*(.+)\s+as\s+(.+)\s*$`is');
        
        $source = $this->source;
        $sourceAlias = $source;
        $sourceMatches = $regex->match($source);
        if($sourceMatches->count()){
            $sourceAlias = $sourceMatches[2]->match();
        }
        
        $target = $this->target;
        $targetAlias = $target;
        $targetMatches = $regex->match($target);
        if($targetMatches->count()){
            $targetAlias = $targetMatches[2]->match();
        }
        
        $join = '';
        if($this->selector){
            $join .= $this->selector .' ';
        }
        $join .= 'JOIN ' . $source . ' ON ';
        $join .= $targetAlias . '.' . $this->innerKey;
        $join .= ' = ';
        $join .= $sourceAlias . '.' . $this->outerKey;
        
        return $join;
    }
    
}