<?php
/*ini_set("display_errors","on");
error_reporting(E_ALL);*/

include_once("root.php");
include_once(ROOT."classes/models/IModel.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/Observable.php");
include_once(ROOT."classes/lib/QueryBuilder.php");

/**
 * Base model (business object) class. Every concrete model is derived from this class.
 * It is assumed that a model can be mapped to a single table -- main table. The relationships are managed in SQL queries.
 */
class MModel extends Observable implements IModel {
	
	/**
	 * Working data set.
	 *
	 * @var RecordSet
	 */
	protected $dataSet;
	/**
	 * Arbitral ID or an array of IDs of the criteria.
	 *
	 * @var mixed
	 */
	protected $id;
	/**
	 * Name of the primary key of the main table.
	 *
	 * @var string
	 */
	protected $pk='id';
	/**
	 * Name of the main table.
	 *
	 * @var string
	 */
	protected $table;
	/**
	 * Column names and their initial values of the main table.
	 *
	 * @var colums
	 */
	protected $columns=array();
	
	/**
	 * Column names and their respective values to be included to query criterion.
	 * Works only with update() method. Note that you can use setId() method if the only
	 * criterion is the primary key.
	 *
	 * @var array
	 */
	protected $criterion=array();
	
	
	/**
	 * Clave primaria de la categoría padre
	 * @var string
	 */
	
	public $parent_id;
	
	function MModel($dataSet=null){
		set_time_limit(15);
		$this->dataSet=$dataSet;
		$this->id=null;
	}
	
	public function setId($value) { 
		if(!is_array($value))$this->id=mysql_real_escape_string($value); 
		else {
			$this->id=array();
			foreach ($value as $v){
				$this->id[]=mysql_real_escape_string($v);
			}
		}
	}
	
	/**
	 * Prapare the SQL query for data retrieval.
	 *
	 */
	protected function setQuery() {}
	/**
	 * Prepare the SQL query for calculating the total number of rows for paging.
	 *
	 */
	protected function setCountQuery() {}
	
	public function load(){
		if($this->dataSet){
			$this->setQuery();
			$this->dataSet->fill();
		}
	}
	
	public function add(){
		$qb=new QueryBuilder($this->table,$this->columns);
		$dao=new DAO();
		$dao->query($qb->add());
		$dao->query("SELECT last_insert_id() AS id FROM `$this->table`");
	    $id=(int)$dao->get(0,"id");
		return $id;
	}
	
	public function update(){
		$qb=new QueryBuilder($this->table,$this->columns);
		$dao=new DAO();
		$id=$this->idToString($this->pk);
		$id=!empty($id)?' AND '.$id:'';
		$query=$qb->update($this->_criterion().$id);
		return $dao->query($query);
	}
	
	public function delete(){
		$qb=new QueryBuilder($this->table);
		$dao = new DAO();
		return $dao->query($qb->delete($this->_where()));
	}
	
	protected function _criterion(){
		$where="WHERE 1";
		foreach($this->criterion as $field=>$value){
			$where.=" AND `$field`='$value'";
		}
		return $where;
	}
	
	public function addCriterion($field, $value){
		$this->criterion[mysql_real_escape_string($field)]=mysql_real_escape_string($value);
	}
	
	/**
	 * Create the WHERE clause for SQL queries. The result clause contains the primary key clause, if the ID is set; and clauses for
	 * each column that is set.
	 *
	 * @return string WHERE clause
	 */
	protected function _where(){
		$where="WHERE 1";
  	
  		$ids=$this->idToString("`$this->table`.`$this->pk`");
		if($ids!='')$where.=" AND $ids";
		
		foreach($this->columns as $var => $value){
			if($value!==null && $value!==false || is_array($value) && !empty($value)){
				if(is_array($value)){
					$where.=" AND `$this->table`.`$var` IN ('".implode("','",$value)."')";
				}else{
					$where.=" AND `$this->table`.`$var`='$value'";
				}
			}
		}
		
		return $where;
	}
	
	public function countAll(){
		if($this->dataSet){
			$this->setCountQuery();
			return $this->dataSet->countAll();
		} else {
			return 0;
		}
	}
	
	public function next(){
		if($this->dataSet){
			return $this->dataSet->next();
		} else {
			return null;
		}
	}
	
	public function reset(){
		if($this->dataSet){
			$this->dataSet->reset();
		}
	}
	
	public function getSize(){
		if($this->dataSet){
			return $this->dataSet->getSize();
		} else {
			return 0;
		}
	}
	
	protected function idToString($field){
		$where="";
		if(is_array($this->id)){
			if(count($this->id)>0){
				$where.="(";
				foreach ($this->id as $v) {
					$where.="$field='$v' OR ";
				}
				$where=substr($where,0,-4).")";
			}
		}elseif($this->id!==null){
			$where.="$field='".$this->id."'";
		} 
		return $where;
	}

	public function __call($m, $a){
		if(strtolower(substr($m,0,3))=='set' && array_key_exists(0,$a)){
			$p=strtolower(substr($m,3));
			if(!empty($a[0])){
				if(function_exists('mysql_real_escape_string')){
					if(is_array($a[0])){
						$this->columns[$p]=array();
						foreach ($a[0] as $k=>$v){
							$this->columns[$p][$k]=mysql_real_escape_string($v);
						}
					}else{
						$this->columns[$p]=mysql_real_escape_string(@$a[0]);
					}
				}else{
					if(is_array($a[0])){
						$this->columns[$p]=array();
						foreach ($a[0] as $k=>$v)$this->columns[$p][$k]=addslashes($v);
					}else{
						$this->columns[$p]=addslashes($a[0]);
					}
				}
			}else{
				$this->columns[$p]=$a[0];
			}
		}elseif(strtolower(substr($m,0,3))=='get'){
			$p=strtolower(substr($m,3));
			if(isset($this->columns[$p]) && is_array($this->columns[$p])){
				$r=array();
				foreach($this->columns[$p] as $k=>$v){
					$r[$k]=stripslashes($v);
				}
				return $r;
			}else{
				return isset($this->columns[$p])?stripslashes($this->columns[$p]):null;
			}
		}
		return true;
	}
	
	public function __get($name){
		if(isset($this->columns[$name])){
			return $this->columns[$name];
		}else{
			return null;
		}
	}
	
	public function getStart() { return $this->dataSet?$this->dataSet->getStart():null; }
	public function getLimit() { return $this->dataSet?$this->dataSet->getLimit():null; }
	public function getOrder() { return $this->dataSet?$this->dataSet->getOrder():null; }
	public function getId() { return $this->id; }
	
	public function setStart($start){ if($this->dataSet) $this->dataSet->setStart($start); }
	public function setLimit($limit){ if($this->dataSet) $this->dataSet->setLimit($limit); }
	public function addOrder($order){ if($this->dataSet) $this->dataSet->addOrder($order); }
}

?>