<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MACLGroupUsers extends MModel{
  
  private $notgroup;
  private $active;
  private $banned;
	
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table='acl_group_users';
    
    $this->columns=array(
    	'acl_groups_id'=>null,
  		'username'=>null,
    );
    
  }
  
  public function setActive($value){ $this->active=(int)$value; }  
  public function setBanned($value){ $this->banned=(int)$value; }  
  public function setNotgroup($value){
  	if(is_array($value)){
  		$this->notgroup=array();
  		foreach($value as $k=>$v) $this->notgroup[$k]=(int)$v;
  	}else{
  		$this->notgroup=(int)$value;
  	}
  }
  
  protected function setQuery(){
	$query="
	SELECT acl_group_users.acl_groups_id
		 , users.* 
	  FROM acl_group_users 
 	  JOIN users
 		ON users.username=acl_group_users.username
 	".$this->_where()."
  GROUP BY acl_group_users.username";
	
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM acl_group_users ".$this->_where()." GROUP BY acl_group_users.username";

  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	if($this->columns['username']!==null)$where.=" AND acl_group_users.username='".$this->columns['username']."'";
  	if($this->active!==null)$where.=" AND users.active='$this->active'";
  	if($this->banned!==null)$where.=" AND users.banned='$this->banned'";
  	
  	if(!empty($this->columns['acl_groups_id'])){
  		if(is_array($this->columns['acl_groups_id'])){
  			$where.= " AND acl_groups_id IN (".implode(",",$this->columns['acl_groups_id']).")";
  		}else{
  			$where.= " AND acl_groups_id != ".(int)$this->columns['acl_groups_id'];
  		}
  	}
  	
  	if(!empty($this->notgroup)){
  		if(is_array($this->notgroup)){
  			$where.= " AND users.username NOT IN 
       ( SELECT username FROM acl_group_users WHERE acl_groups_id IN (".implode(",",$this->notgroup).") )";
  		}else{
  			$where.= " AND users.username NOT IN 
       ( SELECT username FROM acl_group_users WHERE acl_groups_id != $this->notgroup )";
  		}
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