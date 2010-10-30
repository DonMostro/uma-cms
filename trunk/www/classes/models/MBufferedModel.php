<?php
include_once("root.php");

/**
 * Buffered model, buffers the result set data. Can be used to handle multiple queries.
 *
 */
class MBufferedModel extends MModel {
	
	protected $buffer;
	
	function __construct($dataSet=null){
		parent::__construct($dataSet);
		$this->buffer=array();
	}
	
	public function load(){
		if($this->dataSet){
			$this->setQuery();
			$this->dataSet->fill();
			$this->buffer=array();
			while($r=$this->dataSet->next())$this->buffer[$r[$this->pk]]=$r;
		}
	}
	
	public function next(){
		list(, $value) = each($this->buffer);
		return $value;
	}
	
	public function reset(){
		reset($this->buffer);
	}
	
	public function getSize(){
		return count($this->buffer);
	}
	
	protected function clear(){
		$this->buffer=array();
	}
}

?>