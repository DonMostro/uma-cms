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
    
    $this->table=TABLE_PREFIX.'video_types';
    
    $this->columns=array(
    	'videos_id'=>null,
  		'types_id'=>null,
  		'filename'=>null
    );
   
  }
  
  public function setVideosId($value){
	$this->columns['videos_id']=(int)$value; 	
  }

  public function setTypesId($value){
	$this->columns['types_id']=(int)$value;   	
  }
  
  public function setFileName($value){
  	$this->columns['filename']=mysql_escape_string($value);
  }
  
  protected function setQuery(){
  	
    //Get lista de categories
    $query="
    SELECT videos_id, types_id, filename 
	   FROM $this->table 
	".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM $this->table ".$this->_where();

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