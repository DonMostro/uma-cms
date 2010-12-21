<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MImages extends MModel{
  
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'images';
    
    $this->columns=array(
    	'videos_id'=>null,
  		'filename'=>null,
  		'thumbs_id'=>null,
    );
    
  }
  
  protected function setQuery(){
	$query="SELECT * FROM $this->table ".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM $this->table ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  public function add(){
  	
    $this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::add();
  }
  
  public function update(){
	  	
	$this->setState('change_immediate');
	$this->notifyObservers();
		
	return parent::update();
  }
  
  public function delete(){
  	$dao=new DAO();
  	if(!empty($this->columns['videos_id'])){
  		$id='videos_id';
  		$this->setId($this->getVideos_id());
  	}elseif (!empty($this->columns['thumbs_id'])){
  		$id='thumbs_id';
  		$this->setId($this->getThumbs_id());
  	}else{
  		$id='id';
  	}
  	$dao->query("SELECT filename FROM $this->table WHERE ".$this->idToString($id));
	@unlink(ROOT.FILES.'/'.$dao->get(0,'filename'));
	
	$this->setState('change_immediate');
	$this->notifyObservers();
  	
	return parent::delete();
  }
  
}
?>