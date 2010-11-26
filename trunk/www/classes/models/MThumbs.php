<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
/**
 * 
 *  *
 */

class MThumbs extends MModel{
  private $group;
  private $channel;

  function __construct(){
    parent::__construct(new RecordSet());
    $this->dataSet->addOrder(new DataOrder("id", "DESC"));
    $this->table='thumbs';
    $this->columns=array(
    	'videos_id'=>null,
  		'filename'=>null
    );
  }

  public function setVideos_id($value) { 
  	if(is_array($value)){
  		$this->columns['videos_id']=array();
  		foreach ($value as $i=>$v) $this->columns['videos_id'][$i]=(int)$v;
  	}else{
  		$this->columns['videos_id']=(int)$value; 
  	}
  }

  public function setGroup($value) { $this->group=(int)$value; }
  public function setChannel($value) { $this->channel=mysql_real_escape_string($value); }

  protected function setQuery(){
  	if($this->channel!=null){
  		$query="
  		SELECT thumbs.id AS thumbs_id
  			 , videos.title FROM thumbs 
  		JOIN videos 
  		  ON thumbs.videos_id=videos.id
  		   WHERE username='$this->channel'";
  	}elseif($this->group!==null){ 
  		$query="
  		SELECT thumbs.id AS thumbs_id
  		     , videos.title
  		  FROM thumbs
  		JOIN videos
  		       ON videos.id=thumbs.videos_id
  		JOIN group_videos
  			   ON group_videos.videos_id=videos.id
  		JOIN groups
  			   ON groups.id=group_videos.groups_id
  		 WHERE groups.id=$this->group
  		";
  	}elseif($this->id!=null&&!is_array($this->id)) {
  		$query="SELECT * FROM thumbs ".$this->_where()." AND id=".(int)$this->id;
  	} else {
  		$query="SELECT * FROM thumbs ".$this->_where();
  	}
    $this->dataSet->setQuery($query);
  }
  protected function setCountQuery(){
  	if($this->channel!=null){
  		$query="
  		SELECT COUNT(*) 
  		JOIN videos 
  		  ON thumbs.videos_id=videos.id
  		   WHERE username='$this->channel'";
  	} elseif($this->id!=null&&!is_array($this->id)) {
  		$query="SELECT COUNT(*) FROM thumbs ".$this->_where()." AND id=".(int)$this->id;
  	} else {
  		$query="SELECT COUNT(*) FROM thumbs ".$this->_where();
  	}
  	$this->dataSet->setCountQuery($query);
  }

  public function add(){
    $this->setState('change_immediate');
	$this->notifyObservers();
	return parent::add();
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#update()
   */
  
  public function update(){
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	return parent::update();
  }
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#delete()
   */

  public function delete(){
  	if($this->id==null) {
  		$this->setId($this->columns['videos_id']);
  		$ids=$this->idToString("videos_id");
  	}else{
  		$ids=$this->idToString("id");
  	}

  	if($ids!=""){
  		$where="WHERE $ids";
	  	$dao=new DAO();
	  	$query="SELECT filename FROM thumbs $where";
	  	$dao->query($query);
	  	while($row=$dao->getAll()){
	  		if(!empty($row['filename'])&&file_exists(ROOT.THUMBNAILS."/".$row['filename'])){
	  			unlink(ROOT.THUMBNAILS."/".$row['filename']);
	  		}
	  	}
	  	$query="DELETE FROM thumbs $where";
	  	$dao->query($query);
	  	$this->setState('change_immediate');
		$this->notifyObservers();
		return true;
  	}else{
  		return false;
  	}
  }
}
?>