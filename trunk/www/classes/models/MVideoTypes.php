<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MVideos.php");


class MVideoTypes extends MModel{
	
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table='video_types';
    
    $this->columns=array(
    	'name'=>null,
  		'template'=>null,
  		'categories_id'=>null
    );
   
  }

  
  protected function setQuery(){
  	
    //Get lista de categories
    $query="
    SELECT *
	   FROM video_types
	".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM video_types ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  function add(){
    
    $this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::add();
	
  }
  
  function update(){
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	
  	return parent::update();

  }
  
  function delete(){
  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::delete();

  }
  
}
?>