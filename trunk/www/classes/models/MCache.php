<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MVideos.php");
error_reporting(0);

class MCache extends MModel{
	
  private $file;
  
  public function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table='cache';
    
    $this->columns=array(
	  	'name'=>null,
	    'url'=>null,
	    'tt'=>null,
	    'command'=>null
    );
   
  }
  
  public function setFile($value){ $this->file=$value; }
  
  protected function setQuery(){
  	
    $query="
    SELECT cache.*
      FROM cache
	   ".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="
  	SELECT COUNT(*)
      FROM cache
	   ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	$ids=$this->idToString("cache.id");
	if($ids!="")$where.=" AND $ids";
	
	if($this->columns['command']!=null){
		$this->id=$this->columns['command'];
		$controllers=$this->idToString("cache.command");
		if($controllers!="")$where.=" AND $controllers";
	}
	
  	if($this->columns['url']!=null)$where.=" AND url='".$this->columns['url']."'";
  	if($this->columns['tt']!=null)$where.=" AND tt>".$this->columns['tt'];
    return $where;
  }
  
  public function add(){
   	error_reporting(0);
  	$this->columns['name'] = md5(microtime());
    
    $ff = fopen(CACHE.'/'.$this->columns['name'], 'w');
    fwrite($ff, $this->file);
    fclose($ff);
    // chmod($this->file, 666);
	
    return parent::add();
  }
  
  public function delete(){
  	$cache = new MCache();
  	$cache->setCommand($this->columns['command']);
  	$cache->load();
  	while ($row = $cache->next()) {
  		@unlink(ROOT.CACHE.'/'.$row['name']);
  	}
  	return parent::delete();
  }
  
}
?>