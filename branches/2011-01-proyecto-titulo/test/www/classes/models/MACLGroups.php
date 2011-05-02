<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MACLGroups extends MModel{
  
  private $username;
  private $resource_id;
  private $res_type;
	
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'acl_groups';
    $this->table_acl=TABLE_PREFIX.'acl';
    $this->table_acl_group_users=TABLE_PREFIX.'acl_group_users';
    
    $this->columns=array(
    	'name'=>null,
  		'featured'=>null,
  		'videos'=>null,
  		'categories'=>null,
  		'channels'=>null,
  		'favorites'=>null,
  		'playlists'=>null,
  		'subscriptions'=>null,
  		'comments'=>null,
  		'upload'=>null,
  		'download'=>null,
  		'groups'=>null,
  		'v_time'=>null,
  		'views'=>null,
  		'b_time'=>null,
  		'bandwidth'=>null,
      	'u_time'=>null,
  		'uploads'=>null
    );
    
  }
  
  public function setRes_type($value){ 
  	if($value!==null)$this->res_type=mysql_real_escape_string($value); 
  	else $this->res_type=null;
  }
  public function setUsername($value){ 
  	if($value!==null)$this->username=mysql_real_escape_string($value); 
  	else $this->username=null;
  }
  public function setResourceId($value){ $this->resource_id=$value; }
  
  protected function setQuery(){
	$join='';
	$select='*';
  	if($this->username!==null)$join.=" JOIN $this->table_acl_group_users ON $this->table_acl_group_users.acl_groups_id=$this->table.id";
  	if($this->resource_id!==null||$this->res_type!==null){
  		$join.=" JOIN $this->table_acl ON $this->table_acl.$this->table_acl_groups_id=$this->table.id";
  		$select='*,$this->table_acl.access';
  	}
  	
  	$query="SELECT $select FROM $this->table $join ".$this->_where();
	//Debug::write($query);
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$join='';
  	if($this->username!==null)$join.=" JOIN $this->table_acl_group_users ON $this->table_acl_group_users.$this->table_acl_groups_id=$this->table.id";
  	if($this->resource_id!==null||$this->res_type!==null)$join.=" JOIN $this->table_acl ON $this->table_acl.$this->table_acl_groups_id=$this->table.id";
  	
  	$query="SELECT COUNT(*) FROM $this->table $join ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	$ids=$this->idToString("`$this->table`.`$this->pk`");
	if($ids!='')$where.=" AND $ids";
	
	if($this->res_type!==null)$where.=" AND $this->table_acl.res_type='$this->res_type'";
	if($this->username!==null)$where.=" AND $this->table_acl_group_users.username='$this->username'";
	if($this->resource_id!==null)$where.=" AND $this->table_acl.resource_id='".$this->resource_id."'";
	
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