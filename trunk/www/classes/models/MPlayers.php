<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MPlayers extends MModel{

  private $random;
  private $picked;
  private $approved;
  private $type;
  private $table_videos;
  
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'players';
    $this->table_videos=TABLE_PREFIX.'videos';
    
    $this->columns=array(
    	'type'=>null,
  		'code'=>null,
  		'embed'=>null,
  		'browser'=>null
    );

  }
  
  public function setRandom($value) { $this->random=(int)$value; }
  public function setPicked($value) { $this->picked=(int)$value; }
  public function setApproved($value) { $this->approved=(int)$value; }
  public function setType($value)  { $this->type=$value; }
  
  protected function setQuery(){
	$query="SELECT * FROM $this->table ";
	$query.= $this->_where();
	
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
	$query="SELECT COUNT(*) FROM $this->table";
  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where=" WHERE 1";
  	
  	
  	$ids=$this->idToString("$this->table.id");
  	
  	
  	
  	
  	if(!empty($this->type)){	
		$where.=" AND $this->table.type='".mysql_real_escape_string($this->type)."' ";
  	}
  	return $where;
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
