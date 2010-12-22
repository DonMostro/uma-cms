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
 *
 */

class MThumbnails extends MModel{
  private $group;
  private $channel;
  private $table_videos;

  function __construct(){
    parent::__construct(new RecordSet());
    //$this->dataSet->addOrder(new DataOrder("id", "DESC"));
    $this->table=TABLE_PREFIX.'thumbs';
    $this->table_videos=TABLE_PREFIX.'videos';
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
  	if(empty($this->id)){
  		$query="
  		SELECT $this->table.id AS thumbs_id, $this->table.filename 
  			 , $this->table_videos.title, $this->table_videos.id AS videos_id FROM $this->table 
  		JOIN $this->table_videos 
  		  ON $this->table.videos_id=$this->table_videos.id";
  	}elseif($this->id!=null&&!is_array($this->id)) {
  		$query="SELECT * FROM $this->table ".$this->_where()." AND id=".(int)$this->id;
  	} else {
  		$query="SELECT * FROM $this->table ".$this->_where();
  	}
    $this->dataSet->setQuery($query);
  }
  protected function setCountQuery(){
  	if($this->channel!=null){
  		$query="
  		SELECT COUNT(*) 
  		JOIN $this->table_videos 
  		  ON $this->table.videos_id=$this->table_$this->table_videos.id ";
  	} elseif($this->id!=null&&!is_array($this->id)) {
  		$query="SELECT COUNT(*) FROM $this->table ".$this->_where()." AND id=".(int)$this->id;
  	} else {
  		$query="SELECT COUNT(*) FROM $this->table ".$this->_where();
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
	  	$query="SELECT filename FROM $this->table $where";
	  	$dao->query($query);
	  	while($row=$dao->getAll()){
	  		if(!empty($row['filename'])&&file_exists(ROOT.FILES_THUMBNAILS."/".$row['filename'])){
	  			unlink(ROOT.FILES_THUMBNAILS."/".$row['filename']);
	  		}
	  	}
	  	$query="DELETE FROM $this->table $where";
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