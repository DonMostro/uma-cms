<?php
include_once("root.php");
include_once(ROOT."classes/models/IModel.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/Observable.php");
include_once(ROOT."classes/lib/QueryBuilder.php");

/**
 * Clase base de Modelos. Todos las clases modelos extienden esta.
 * Se asume que mapea una sola tabla. Las relaciones son manejadas en l&oacute;gica de base de datos.
 */
class MModel extends Observable implements IModel {
	
	/**
	 * Recordset a usar.
	 *
	 * @var RecordSet
	 */
	protected $dataSet;
	/**
	 * ID simple o array de IDs.
	 *
	 * @var mixed
	 */
	protected $id;
	/**
	 * Nombre de la clave primaria de la tabla principal.
	 *
	 * @var string
	 */
	protected $pk='id';
	/**
	 * Nombre de la tabla principal.
	 *
	 * @var string
	 */
	protected $table;
	/**
	 * Columnas de la tabla con vallores iniciales.
	 *
	 * @var colums
	 */
	protected $columns=array();
	
	/**
	 * Nombres de columna y respectivos valores para ser incluidos en el criterio de la consulta.
	 * Funciona con el m&eacute;todo update(). Se puede usar setId() si el criterio es clave primaria
	 *
	 * @var array
	 */
	protected $criterion=array();
	
	function MModel($dataSet=null){
		set_time_limit(15);
		$this->dataSet=$dataSet;
		$this->id=null;
	}
	
	/**
	 * Setea clave primaria para ser usada como par&aacute;metro de la consulta SQL.
	 * @param $value
	 * @return unknown_type
	 */
	
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
	 * Query SQL para el despliegue de datos.
	 *
	 */
	protected function setQuery() {}
	/**
	 * Cuenta el n&uacute;mero de filas a paginar.
	 *
	 */
	protected function setCountQuery() {}
	
	/**
	 * (non-PHPdoc)
	 * @see classes/models/IModel#load()
	 */
	
	public function load(){
		if($this->dataSet){
			$this->setQuery();
			$this->dataSet->fill();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see classes/models/IModel#add()
	 */
	public function add(){
		$qb=new QueryBuilder($this->table,$this->columns);
		$dao=new DAO();
		$dao->query($qb->add());
		exit($qb->add());
		$dao->query("SELECT last_insert_id() AS id FROM `$this->table`");
	    $id=(int)$dao->get(0,"id");
		return $id;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see classes/models/IModel#update()
	 */
	
	public function update(){
		$qb=new QueryBuilder($this->table,$this->columns);
		$dao=new DAO();
		$id=$this->idToString($this->pk);
		$id=!empty($id)?' AND '.$id:'';
		$query=$qb->update($this->_criterion().$id);
		return $dao->query($query);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see classes/models/IModel#delete()
	 */
	
	public function delete(){
		$qb=new QueryBuilder($this->table);
		$dao = new DAO();
		return $dao->query($qb->delete($this->_where()));
	}
	
	/**
	 * WHERE SQL como criterio de consulta o modificaci&oacute;n
	 * @return string
	 */
	
	protected function _criterion(){
		$where="WHERE 1";
		foreach($this->criterion as $field=>$value){
			$where.=" AND `$field`='$value'";
		}
		return $where;
	}
	
	/**
	 * A&ntilde;ade criterio de consulta 
	 * @param $field
	 * @param $value
	 */
	
	public function addCriterion($field, $value){
		$this->criterion[mysql_real_escape_string($field)]=mysql_real_escape_string($value);
	}
	
	/**
	 * Crea la clausula WHERE para consultas SQL.
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
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#countAll()
	 */
	
	public function countAll(){
		if($this->dataSet){
			$this->setCountQuery();
			return $this->dataSet->countAll();
		} else {
			return 0;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#next()
	 */
	
	public function next(){
		if($this->dataSet){
			return $this->dataSet->next();
		} else {
			return null;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#reset()
	 */
	
	public function reset(){
		if($this->dataSet){
			$this->dataSet->reset();
		}
	}
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#getSize()
	 */
	public function getSize(){
		if($this->dataSet){
			return $this->dataSet->getSize();
		} else {
			return 0;
		}
	}
	
	/**
	 * 
	 * @param $field
	 * @return unknown_type
	 */
	
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
	
	/**
	 * 
	 * @param $m
	 * @param $a
	 * @return boolean
	 */

	public function __call($m, $a){
		if(strtolower(substr($m,0,3))=='set' && array_key_exists(0,$a)){
			$p=strtolower(substr($m,3));
			if(!empty($a[0])){
				if(function_exists('mysql_real_escape_string')){
					if(is_array($a[0])){
						$this->columns[$p]=array();
						foreach ($a[0] as $k=>$v)$this->columns[$p][$k]=mysql_real_escape_string($v);
					}else{
						$this->columns[$p]=mysql_real_escape_string($a[0]);
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
	
	/**
	 * M&eacute;todo m&aacute;gico PHP 5 para obtener cualquier columna
	 * @param $name
	 * @return unknown_type valor de campo
	 */
	
	public function __get($name){
		if(isset($this->columns[$name])){
			return $this->columns[$name];
		}else{
			return null;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#getStart()
	 */
	public function getStart() { return $this->dataSet?$this->dataSet->getStart():null; }
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#getLimit()
	 */
	public function getLimit() { return $this->dataSet?$this->dataSet->getLimit():null; }
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#getOrder()
	 */
	public function getOrder() { return $this->dataSet?$this->dataSet->getOrder():null; }
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#getId()
	 */
	public function getId() { return $this->id; }
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#setStart($start)
	 */
	public function setStart($start){ if($this->dataSet) $this->dataSet->setStart($start); }
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#setLimit($limit)
	 */
	public function setLimit($limit){ if($this->dataSet) $this->dataSet->setLimit($limit); }
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/IModel#addOrder($order)
	 */
	public function addOrder($order){ if($this->dataSet) $this->dataSet->addOrder($order); }
}

?>