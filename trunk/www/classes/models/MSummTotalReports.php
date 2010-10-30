<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MSummTotalReports extends MModel {
  
  private $resolved;
  
  function __construct(){
    parent::__construct(new RecordSet());

  }
  
  public function setResolved($value) { $this->resolved=(int)$value; }
 
  protected function setQuery(){
  	
	$where="WHERE 1";
	
	if($this->resolved!==null)$where.=" AND resolved=$this->resolved";

	$query="SELECT COUNT(*) FROM reports $where";
	Debug::write($query);
    $this->dataSet->setQuery($query);
  }
  
}
?>