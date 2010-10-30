<?php

class QueryBuilder {
	private $table;
	private $columns;
	
	public function __construct($table, $columns=array()){
		$this->table=$table;
		$this->columns=$columns;
	}
	
	public function add(){
		$fields='';
		$values='';
		foreach($this->columns as $var => $value){
			if($value!==null&&$value!==false){
				$fields.=',`'.$var.'`';
				$values.=',\''.$value.'\'';
			}
		}
		$fields=ltrim($fields,',');
		$values=ltrim($values,',');
		$query="INSERT INTO `$this->table` ($fields) VALUES ($values)";
	//	if($_SERVER['SCRIPT_NAME'] == '/admin/index.php' && $_GET['p'] == 'top_carrusel_test') die ($query);
		return $query;
	}
	
	public function update($where=''){
		$fields='';
		foreach($this->columns as $var => $value){
			if($value!==null&&$value!==false){
				$fields.=',`'.$var.'`=\''.$value.'\'';
			}
		}
		$fields=ltrim($fields,',');
		$query="UPDATE `$this->table` SET $fields $where";
	//	if($_SERVER['SCRIPT_NAME'] == '/admin/index.php' && $_GET['p'] == 'top_carrusel_test') die ($query);
		return $query;
	}
	
	public function delete($where=''){
  		$query = "DELETE FROM `$this->table` $where";
  		return $query;
	}
}
?>