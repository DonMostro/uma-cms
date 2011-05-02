<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MFavorites.php");
include_once(ROOT."classes/models/MComments.php");
include_once(ROOT."classes/models/MChannels.php");
include_once(ROOT."classes/models/MChannelElements.php");
include_once(ROOT."classes/lib/RecordSet.php");


class MUser extends MModel{
  
  private $search;
  private $tt_begin;
  private $tt_end;
  
  function __construct(){
  	parent::__construct(new RecordSet());
  	
  	$this->table='users';
  	$this->pk='username';
  	
  	$this->columns=array(
  		'username'=>null,
  		'password'=>null,
  		'email'=>null,
  		'ip'=>null,
  		'perms'=>null,
  		'banned' => null,
  		'active' => null,
  		'email_token' => null
  	);

  }
  
  function setSearch($value) {
  	$search=str_replace("%"," ",$value);
    $search=str_replace("_"," ",$search);
    $this->search=mysql_real_escape_string(trim($search));
  }
  
  public function setTt_begin($value){ $this->tt_begin=(int)$value; }
  public function setTt_end($value){ $this->tt_end=(int)$value; }
  
  function getSearch() { return $this->search; }
  
  public function login(){
  	$dao=new DAO();
    $dao->query("
    SELECT * 
      FROM users 
     WHERE username='".$this->columns['username']."' 
       AND password='".$this->columns['password']."' 
       AND banned!='1'
       AND active='1'");
    return $dao->rowCount();
  }
  
  protected function setQuery(){
	
	$query="SELECT * FROM users ".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM users ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	$ids=$this->idToString("users.username");
	if($ids!="")$where.=" AND $ids";
  	
    if($this->columns['active']!==null)$where.=" AND active='".$this->columns['active']."'";
    if($this->columns['banned']!==null)$where.=" AND banned='".$this->columns['banned']."'";
    if($this->columns['username']!="")$where.=" AND username='".$this->columns['username']."'";
    if($this->tt_begin!==null)$where.=" AND joined>".$this->tt_begin;
    if($this->tt_end!==null)$where.=" AND joined<=".$this->tt_end;
	if($this->search!="")$where.=" AND username LIKE '%$this->search%'";
    return $where;
  }
  
  public function add(){
  	$dao=new DAO();
    $dao->query("SELECT * FROM users WHERE username='".$this->columns['username']."'");
    if($dao->rowCount()>0)return false;
    else{
      $sql="
      INSERT INTO users 
      	   ( username
      	   , password
      	   , email
      	   , joined
      	   , perms
      	   , ip
      	   , active
      	   , email_token )
      	   VALUES
      	   ( '".$this->columns['username']."'
      	   , '".md5($this->columns['password'])."'
      	   , '".$this->columns['email']."'
      	   , ".time()."
      	   , ".(int)$this->columns['perms']."
      	   , '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'
      	   , '".$this->columns['active']."'
      	   , '".$this->columns['email_token']."' )";
	  $dao->query($sql);
	  
	  $channel=new MChannels();
	  $channel->setUsername($this->getUsername());
	  $channel->setThumbs_id(0);
	  $channel->add();
	  
	  $channel_elements=new MChannelElements();
	  $channel_elements->setUsername($this->getUsername());
	  
	  $channel_elements->setElements_id('channel_latest_videos');
	  $channel_elements->setOrder(3);
	  $channel_elements->setSection('right');
	  $channel_elements->setLimit(9);
	  $channel_elements->add();
	  
	  $channel_elements->setElements_id('channel_friends');
	  $channel_elements->setOrder(7);
	  $channel_elements->setSection('right');
	  $channel_elements->setLimit(9);
	  $channel_elements->add();
	  
	  $channel_elements->setElements_id('channel_comments');
	  $channel_elements->setOrder(8);
	  $channel_elements->setSection('left');
	  $channel_elements->setLimit(12);
	  $channel_elements->add();
	  
	  $this->setState('change_immediate');
	  $this->notifyObservers();
	  
	  return true;
	}
  }
  
  public function update(){
  	if(strlen($this->columns['password'])==32)$this->columns['password']=null;
  	if($this->columns['password']!==null)$this->columns['password']=md5($this->getPassword());
  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	  
  	return parent::update();
  }
  
  public function delete(){
  	
  	$channels=new MChannels();
  	$channels->setUsername($this->getUsername());
  	$channels->delete();
  	
  	$channel_elements=new MChannelElements();
  	$channel_elements->setUsername($this->getUsername());
  	$channel_elements->delete();
  	
  	$favorites=new MFavorites();
  	$favorites->setUsername($this->getUsername());
  	$favorites->delete();
  	
  	$comments=new MComments();
  	$comments->setUsername($this->getUsername());
  	$comments->delete();
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	
	return parent::delete();
  }
  
  public function reset_password(){
  	$ids=$this->idToString("username");
  	if($ids!=""){
  		$where="WHERE $ids";
	  	$dao=new DAO();
	  	$query="SELECT * FROM users WHERE $ids AND email='".$this->columns['email']."'";
	  	$dao->query($query);
	  	if($dao->rowCount()==0){
	  		return false;
	  	}else{
	  		$password=substr(md5(microtime()),-10,10);
		  	$query="UPDATE users SET password='".md5($password)."' $where";
		  	$dao->query($query);
		  	return $password;
	  	}
  	} else {
  		return false;
  	}
  }
  
  
}

?>