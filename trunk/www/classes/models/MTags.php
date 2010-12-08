<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MBufferedModel.php");


class MTags extends MBufferedModel{
	
  private $search;
  private $exclude;
  private $top;
  private $categories_id;
  private $username;
  private $tt;
  private $table_video_tags;
  private $table_videos;
  private $table_categories;
  
  function __construct(){
    parent::__construct(new RecordSet());
    $this->addOrder(new DataOrder('tag','ASC'));
    
    $this->table=TABLE_PREFIX.'tags';
    $this->table_video_tags=TABLE_PREFIX.'video_tags'; 
    
    $this->columns=array(
    	'tag'=>null,
  		'videos_id'=>null
    );
    
    $this->search="";
    $this->top=false;
  }
  
  public function setTop($value) { $this->top=(int)$value; }
  public function setTt($value) { $this->tt=(int)$value; }
  public function setCategories_id($value) { $this->categories_id=(int)$value; }
  public function setExclude($value) { $this->exclude=mysql_real_escape_string($value); }
  public function setUsername($value) { $this->username=mysql_real_escape_string($value); }
  public function setSearch($value) {
  	$search=str_replace("%"," ",$value);
    $search=str_replace("_"," ",$search);
    $this->search=mysql_real_escape_string(trim($search));
  }
  
  public function getSearch() { return $this->search; }
  public function getTop() { return $this->top; }
  
  protected function setQuery(){
 	
  	if($this->top || $this->categories_id){
  		$query="
  		SELECT $this->table.id
  		     , $this->table.tag
  		     , COUNT($this->table.id) AS hits
  		   FROM $this->table
  		JOIN $this->table_video_tags
  			   ON $this->table_video_tags.tags_id=$this->table.id
  		JOIN videos
  			   ON $this->table_videos.id=$this->table_video_tags.videos_id
  	 	".$this->_where()."
  		   GROUP BY $this->table.id, $this->table.tag";
  		
  	}elseif($this->columns['videos_id']==0) {
  		$query="SELECT * FROM $this->table ".$this->_where();
  		
  	} else {
  		$query="
  		SELECT *,$this->table.id AS id 
  		   FROM $this->table 
  		JOIN $this->table_video_tags 
  		  ON $this->table.id=$this->table_video_tags.tags_id
  		   WHERE videos_id=".(int)$this->columns['videos_id'];
  					
	}
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	if($this->columns['videos_id']==0) {
  		$query="SELECT COUNT(*) FROM $this->table ".$this->_where();
  	} else {
  		$query="
  		SELECT COUNT(*)
  		   FROM $this->table 
  		JOIN $this->table_video_tags  
  		  ON $this->table.id=$this->table_video_tags.tags_id
  		   WHERE videos_id=".(int)$this->columns['videos_id'];			
	}

  	$this->dataSet->setCountQuery($query);
  }
  
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	$ids=$this->idToString("tags.id");
	if($ids!="")$where.=" AND $ids";
  	
	if($this->top && $this->search){
		$tagstr='';
		$tags=explode(" ",$this->search);
        foreach($tags as $tag)$tagstr.=" LTRIM(RTRIM(LOWER($this->table_videos.tags))) LIKE '%".strtolower($tag)."%' OR ";
        $tagstr=substr($tagstr,0,-4);
		$where.=" AND ( ( $tagstr ) OR LTRIM(RTRIM(LOWER($this->table_videos.title))) LIKE '%".strtolower($this->search)."%')";
	}
  	elseif($this->search!="")$where.=" AND LTRIM(RTRIM(LOWER(tag))) LIKE '%".strtolower($this->search)."%'";
  	
  	if($this->exclude){
		$tagstr='';
		$tags=explode(" ",trim($this->exclude));
        foreach($tags as $tag)$tagstr.="LOWER($this->table_videos.tags) NOT LIKE '%".strtolower($tag)."%' AND ";
        $tagstr=substr($tagstr,0,-4);
		$where.=" AND $tagstr AND LOWER($this->table_videos.title) NOT LIKE '%".strtolower($this->exclude)."%'";
	}
  	
  	if($this->top || $this->categories_id)$where.=" AND $this->table_videos.approved='1'";
  	
	if($this->categories_id){
		$strSQL = "SELECT children FROM $this->table_categories WHERE id=$this->categories_id AND children <> '' ;"; 			
	$qry = mysql_query($strSQL);
		if(mysql_num_rows($qry) > 0){
			$row = mysql_fetch_array($qry);
			$strIN = substr($row['children'], 1);
			$where.=" AND $this->table_videos.categories_id IN ($strIN)";		
	  	}else{	
	  		$where.=" AND $this->table_videos.categories_id=$this->categories_id";
	  	}
  	}
  	
  	if( ($this->top || $this->categories_id) && $this->tt)$where.=" AND $this->table_videos.tt > $this->tt";
  	
		  	
    return $where;
  }
  
  public function update(){
  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
  	return parent::update();
  }
  
  public function delete(){
  	$ids=$this->idToString("tags_id");
  	if($ids!=""){
  		//Remove related rows from video_tags
	  	$dao=new DAO();
	  	$query="DELETE FROM $this->table_videos WHERE $ids";
	  	$dao->query($query);
	  		  	
	  	//Remove tag
	  	$ids=$this->idToString("id");
	  	$query="DELETE FROM $this->table WHERE $ids";
	  	$dao->query($query);
	  	
	  	$this->setState('change_immediate');
		$this->notifyObservers();
		
		return true;
  	}else{
  		return false;
  	}
  }
  
  public function load(){
  	$tags=array();
  	if($this->dataSet){
		$this->setQuery();
		$this->dataSet->fill();
		$this->buffer=array();
		$max=1;
		while($r=$this->dataSet->next()){
			if(!empty($r['tag'])){
				if(!isset($r['hits']))$r['hits']=0;
				if($r['hits']>$max)$max=$r['hits'];
				$r['max']=&$max;
				$this->buffer[]=$r;
				$tags[]=$r['tag'];
			}
		}
		if($this->top!=2){
			array_multisort($tags, SORT_ASC, $this->buffer);
		}
	}
  }
  
}
?>