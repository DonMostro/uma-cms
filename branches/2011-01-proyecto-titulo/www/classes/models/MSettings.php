<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MSettings extends MModel{
  
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'settings';
    
    $this->columns=array(
    	'value'=>null,
  		'group'=>null
    );
    
  }
  
  public function getInstance(){
  	static $me;
  	if($me==null){
  		$me=new MSettings();
  	}
  	return $me;
  }
  
  public function loadGroups(){
  	$this->dataSet->setQuery("SELECT DISTINCT `group` FROM $this->table");
  	$this->dataSet->fill();
  }
  
  protected function setQuery(){
	
    $query="SELECT * FROM $this->table ".$this->_where();
    
    $this->dataSet->setQuery($query);
    
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM $this->table ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  public function update(){
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
		
	return parent::update();
  }
  
}
?>
