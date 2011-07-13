<?php
include_once("root.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Types.php");

/**
*
* Clase RecordSet se encarga de la formaci�n y ejecuci�n del SQL.
*
*/
class RecordSet{
  
  var $dao;
  var $query;
  var $countQuery;
  var $start;
  var $limit;
  var $order;
  
  function RecordSet(){
	$this->dao=new DAO();
	$this->size=0;
	$this->query='';
	$this->countQuery='';
	$this->start=0;
	$this->limit=0;
	$this->order=array();
  }
  
  /**
   * 
   * Prepara la query final sobre la base de $start, $limit y $order propiedades de orden y lo ejecuta.
   * 
   */
  function fill(){
  	$limitString="";
  	if($this->limit!=0)$limitString="LIMIT $this->start, $this->limit";
  	$orderString="";
  	$c=count($this->order);
  	if($c!=0){
  		$orderString="ORDER BY";
  		for($i=$c-1; $i>=0; $i--){
  			$orderString.=" ".$this->order[$i]->getValue().",";
  		}
  		$orderString=substr($orderString,0,-1);
  	}

	if($this->query!="")$this->dao->query($this->query." $orderString $limitString");
  }
  
  /**
   * Cuenta el n�mero total de filas del objeto
   * @return int
   */
  
  function countAll(){
  	if(!empty($this->countQuery)){
			$this->dao->query($this->countQuery);
			return $this->dao->get(0,'COUNT(*)');
	}else{
			return 0;
	}
  }
  /**
   * Mueve el puntero al siguiente registro
   * @return DAO
   */
  
  function next(){
    return $this->dao->getAll();
  }
  
  function reset(){
  	$this->dao->seek(0);
  }
  
  function getSize(){
  	return $this->dao->rowCount();
  }
  
  function getStart() { return $this->start; }
  function getLimit() { return $this->limit; }
  function getOrder() { 
  	if(count($this->order)>0){
  		return $this->order[count($this->order)-1]->field; 
  	}else{
  		return null;
  	}
  }
  
  function setQuery($query){ $this->query=$query; }
  function setCountQuery($query){ $this->countQuery=$query; }
  function setStart($start){ $this->start=(int)$start; }
  function setLimit($limit){ $this->limit=(int)$limit; }
  function addOrder($order){ $this->order[]=$order; }
}
?>