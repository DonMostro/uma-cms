<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MMenu extends MModel {
	
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'menu';
    
    $this->columns=array(
    	'parent_id'=>null,
  		'title'=>null,
  		'url'=>null,
  		'approved'=>false,
  		'header'=>false,
  		'footer'=>false,
  		'menu_order'=>null
    );
  }
  
  protected function setQuery(){
  	if($this->columns['parent_id']!==null){
  		$query="SELECT * FROM $this->table ".$this->_where();
  	}elseif($this->columns['url']!==null){
  		$query="SELECT * 
  				FROM $this->table
  				WHERE parent_id = (
  				SELECT parent_id FROM $this->table WHERE $this->table.url='".$this->columns['url']."' LIMIT 1)";
    }elseif ($this->columns['title']!=null){
  		$query="SELECT $this->table.title AS title, t1.title AS parent_title  
  				FROM $this->table JOIN $this->table AS t1 ON $this->table.parent_id=t1.id
  				WHERE $this->table.title='".$this->columns['title']."'";
  	} else {
  		$query="SELECT * FROM $this->table ".$this->_where();
  	}
  	
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	if($this->columns['parent_id']!==null){
  		$query="SELECT COUNT(*) FROM $this->table ".$this->_where();
  	}elseif($this->columns['url']!==null){
  		$query="SELECT COUNT(*) 
  				   FROM $this->table
  				   WHERE parent_id = (
  					   SELECT parent_id 
  					      FROM $this->table 
  					      WHERE $this->table.url = '".$this->columns['url']."' 
  					      LIMIT 1 )";
    }elseif ($this->columns['title']!=null){
  		$query="SELECT COUNT(*)  
  				   FROM $this->table 
  				JOIN $this->table AS t1 
  				  ON $this->table.parent_id=t1.id
  				   WHERE $this->table.title='".$this->columns['title']."'";
  	} else {
  		$query="SELECT COUNT(*) FROM $this->table";
  	}

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