<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MSummTotalCategories extends MModel {
  
  
  function __construct(){
    parent::__construct(new RecordSet());
	
  }
  
 
  protected function setQuery(){
	
	$query="SELECT COUNT(*) FROM categories";

    $this->dataSet->setQuery($query);
  }
  
}
?>