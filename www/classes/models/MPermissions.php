<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");

/**
 * Permisos del usuario sobre los recursos
 *
 */
class MPermissions extends MModel{
  
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'permissions';
    
    $this->columns=array(
    	'resource'=>null,
		'level'=>null
    );
    
  }
  
  protected function setQuery(){

    $query="
	SELECT * FROM $this->table ".$this->_where()."
	";
	
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="
	SELECT COUNT(*) FROM $this->table 
	";

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
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
  
	return parent::delete();
  }
  
}
?>