<?php
include_once("root.php");

/**
 * Buffered model, almacena en meoria el resultado. 
 * Esto Sirve para cuando se manejen multiples queries.
 *
 */
class MBufferedModel extends MModel {
	
	protected $buffer;
	
	/**
	 * constructor
	 * @param $dataSet
	 */
	
	function __construct($dataSet=null){
		parent::__construct($dataSet);
		$this->buffer=array();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#load()
	 */
	
	public function load(){
		if($this->dataSet){
			$this->setQuery();
			$this->dataSet->fill();
			$this->buffer=array();
			while($r=$this->dataSet->next())$this->buffer[$r[$this->pk]]=$r;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#next()
	 */
	
	public function next(){
		list(, $value) = each($this->buffer);
		return $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#reset()
	 */
	
	public function reset(){
		reset($this->buffer);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#getSize()
	 */
	
	public function getSize(){
		return count($this->buffer);
	}
	
	/**
	 * Limpia el buffer
	 */
	
	protected function clear(){
		$this->buffer=array();
	}
}

?>