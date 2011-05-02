<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MVideos.php");


class MModelCache extends MModel{

  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table='modelcache';
    
    $this->columns=array(
    	'data'=>null,
  		'model'=>null
    );
    
  }
  
  protected function setQuery(){
  	
    $query="
    SELECT modelcache.*
      FROM modelcache
	   ".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="
  	SELECT COUNT(*)
      FROM modelcache
	   ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
}
?>