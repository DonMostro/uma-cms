<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MSummTotalVideos extends MModel {
  
  private $approved;
  private $reported;
  
  function __construct(){
    parent::__construct(new RecordSet());

    $this->approved=null;
    $this->reported=0;
  }
  
  public function setReported($value) { $this->reported=(int)$value; }
  public function setApproved($value) { $this->approved=(int)$value; }

  public function getReported() { return $this->reported; }
  public function getApproved() { return $this->approved; }
 
  protected function setQuery(){
  	
	$where="WHERE 1";
	
	if($this->approved!==null)$where.=" AND approved='$this->approved'";
	if($this->reported!=0)$where.=" AND reported='$this->reported'";

	$query="SELECT COUNT(*) FROM videos $where";
    $this->dataSet->setQuery($query);
  }
  
}
?>