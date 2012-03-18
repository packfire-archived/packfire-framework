<?php
pload('ILinq');

/**
 * LINQ operations on a list or collection of objects or arrays
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 * @todo re-implementation required. store all the "actions" and perform only 
 *       at execution to allow join and group to work properly
 */
class pLinqObject implements ILinq {
    
    private $from;
    
    private $source;
    
    public function in($source){
        $this->source = array();
        foreach($source as $item){
            $this->source[] = array(
                $this->from => $item
            );
        }
        return $this;
    }
    
    public function from($name){
        $this->from = $name;
        return $this;
    }
    
    public function where($conditionFunc){
        foreach($this->source as $k => $v){
            if(!$conditionFunc($v)){
                unset($this->source[$k]);
            }
        }
        return $this;
    }
    
    public function orderBy($fieldSelector){
        $sorter = new pObjectSelectedFieldComparator($fieldSelector);
        usort($this->source, array($sorter, 'compare'));
        return $this;
    }
    
    public function thenBy($fieldSelector){
        return $this->orderBy($fieldSelector);
    }
    
    public function orderByDesc($fieldSelector){
        $sorter = new pObjectSelectedFieldComparator($fieldSelector);
        ursort($this->source, array($sorter, 'compare'));
        return $this;
    }
    
    public function thenByDesc($fieldSelector){
        return $this->orderByDesc($fieldSelector);
    }
    
    /**
     *
     * @param type $mapper either function or array
     */
    public function select($mapper = null){
        $data = array();
        if(is_callable($mapper)){
            foreach($this->source as $row){
                $data[] = $mapper($row);
            }
        }elseif(is_array($mapper)){
            
        }elseif(is_string($mapper)){
            foreach($this->source as $row){
                $data[] = $row[$mapper];
            }
        }else{
            $data = $this->source;
        }
        return $data;
    }
    
    public function join($subject, $name, $conditionFunc){
        // TODO join
    }
    
    public function distinct(){
        array_unique($this->source);
    }
    
    public function group($fieldSelector){
        // TODO group
    }
    
    public function count($conditionFunc = null){
        $count = 0;
        if(is_callable($conditionFunc)){
            foreach($this->source as $k => $v){
                if($conditionFunc($v)){
                    $count++;
                }
            }
        }else{
            $count = count($this->source);
        }
        return $count;
    }
    
    public function sum($fieldSelector = null){
        $sum = 0;
        if(is_callable($fieldSelector)){
            foreach($this->source as $row){
                $sum += $fieldSelector($row);
            }
        }else{
            $sum = array_sum($this->source);
        }
        return $sum;
    }
    
    public function min($fieldSelector = null){
        $min = null;
        if(is_callable($fieldSelector)){
            foreach($this->source as $row){
                $rowValue = $fieldSelector($row);
                if($min === null || $rowValue < $rowValue){
                    $min = $rowValue;
                }
            }
        }else{
            $min = min($this->source);
        }
        return $min;
    }
    
    public function max($fieldSelector = null){
        $max = null;
        if(is_callable($fieldSelector)){
            foreach($this->source as $row){
                $rowValue = $fieldSelector($row);
                if($max === null || $rowValue > $max){
                    $max = $rowValue;
                }
            }
        }else{
            $max = max($this->source);
        }
        return $max;
    }
    
    public function average($fieldSelector = null){
        $avg = null;
        if(is_callable($fieldSelector)){
            $avg = $this->sum($fieldSelector) / $this->count($fieldSelector);
        }else{
            $avg = $this->sum() / $this->count();
        }
        return $avg;
    }
    
    public function limit($offset, $length = null){
        if(func_num_args() == 2){
            array_splice($this->source, $offset, $length);
        }else{
            array_splice($this->source, $offset);
        }
    }
    
    public function first(){
        $row = reset($this->source);
        if($row === false){
            return null;
        }
        return $row;
    }
    
    public function last(){
        $row = end($this->source);
        if($row === false){
            return null;
        }
        return $row;
    }
}