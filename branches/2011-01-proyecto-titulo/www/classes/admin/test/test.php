<?php
include_once('root.php');
include_once(ROOT.'config.php');

class Test{
	var $value;
	var $id;
	
	function Test($value=''){
		$this->value=$value;
	}
	
	function setValue($value){
		$this->value=$value;
	}
	
	function setId($id){
		$this->id=$id;
	}
	
	function display(){
		return '<input value="Test" type="button" onclick="popup(\'index.php?p=settings&f='.urlencode(get_class($this)).'&v=\'+escape(document.getElementById(\'edit'.$this->id.'_0\').value)+\'&ajax\')" />';
	}
	
	function run(){
		
	}
}
?>