<?php
/**
 * Constructor de consultas SQL 
 * @author Rodrigo
 *
 */
class QueryBuilder {
	private $table;
	private $columns;
	
	/**
	 * constructor
	 * @param $table tabla
	 * @param $columns array
	 */
	
	public function __construct($table, $columns=array()){
		$this->table=$table;
		$this->columns=$columns;
	}
	
	/**
	 * Constructor de query INSERT
	 * @return string SQL
	 */
	
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
		return $query;
	}
	
	/**
	 * Constructor de queries UPDATE
	 * @param $where string
	 * @return string SQL
	 */
	
	public function update($where=''){
		$fields='';
		foreach($this->columns as $var => $value){
			if($value!==null&&$value!==false){
				$fields.=',`'.$var.'`=\''.$value.'\'';
			}
		}
		$fields=ltrim($fields,',');
		$query="UPDATE `$this->table` SET $fields $where";
		return $query;
	}
	
	/**
	 * Constructor de queries DELETE
	 * @param $where string
	 * @return string SQL
	 */
	
	public function delete($where=''){
  		$query = "DELETE FROM `$this->table` $where";
  		return $query;
	}
}
?>