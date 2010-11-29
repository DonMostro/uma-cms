<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MLanguages extends MModel{
  
  function __construct(){
    parent::__construct(new RecordSet());
    $this->addOrder(new DataOrder("code","ASC"));
    
    $this->table='lang';
    
    $this->columns=array(
    	'approved'=>null,
  		'code'=>null
    );
  }
  
  protected function setQuery(){
  	
    //Obtener la lista de categorías
    $query="SELECT * FROM lang ".$this->_where();
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM lang ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  public function add(){
    
    $this->setState('change_immediate');
	$this->notifyObservers();
	
	$id=parent::add();
	
	if($id){
		$dao=new DAO();
		$dao->query("INSERT INTO ltext SELECT NULL,$id,code,`string` FROM ltext WHERE lang_id=1");
	}
	
	return $id;
	
  }
  
  public function update(){
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
  	return parent::update();
  }
  
  public function delete(){
  	$ids=$this->idToString("id");
  	if($ids!=""){
  		$dao=new DAO();
  		
  		$lids=$this->idToString("lang_id");
  		$dao->query("DELETE FROM ltext WHERE $lids");
	  	
	  	$this->setState('change_immediate');
		$this->notifyObservers();
		
		return parent::delete();
  	}else{
  		return false;
  	}
  }
  
}
?>