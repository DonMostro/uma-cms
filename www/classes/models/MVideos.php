<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MCategories.php");
include_once(ROOT."classes/models/MThumbnails.php");
include_once(ROOT."classes/models/MTags.php");

/**
 * Clase base de videos
 * @author Rodrigo
 *
 */

class MVideos extends MModel {
  
	private $search;
	private $exclude;
	private $get_childrens;
	private $table_categories;
	private $table_thumbs;
	private $table_tags;
	private $table_hits;
	private $table_video_types;
	private $table_videos_categories;
	private $table_video_tags;
	
	/**
	 * Constructor
	 *	  
	 * */
	
	function __construct(){
    	parent::__construct(new RecordSet());
    	$this->table=TABLE_PREFIX.'videos';
		$this->table_categories=TABLE_PREFIX.'categories';
		$this->table_thumbs=TABLE_PREFIX.'thumbs';
		$this->table_tags=TABLE_PREFIX.'tags';
		$this->table_hits=TABLE_PREFIX.'video_hits';
		$this->table_video_types=TABLE_PREFIX.'video_types';
		$this->table_videos_categories=TABLE_PREFIX.'videos_categories';
		$this->table_video_tags=TABLE_PREFIX.'video_tags';
	
		$this->columns=array(
			'categories_id'=>null,
	  		'approved'=>false,
	  		'title'=>null,
	  		'description'=>null,
	  		'tags'=>null,
	  		'type'=>null,
	  		'tt'=>null,
	  		'duration'=>null,
	  		'frame'=>null,
	  		'orig_file'=>null,
	  		'size'=>null,
			'rate'=>null
		);
	
    	$this->search="";
	}
	
	/**
	 * Setea categories_id
	 * @param array / integer
	 */
  
	public function setCategories_Id($value) { 
  		if(is_array($value)){
  			$this->columns['categories_id']=array();
  			foreach ($value as $k=>$v){
  				$this->columns['categories_id'][$k]=(int)$v;
  			}
  		}else{
  			$this->columns['categories_id']=(int)$value; 
  		}
	}
	
	/**
	 * Setea parent_id
	 * @param $value array / integer
	 */
  
	public function setParent_Id($value) { 
  		if(is_array($value)){
  			$this->columns['parent_id']=array();
  			foreach ($value as $k=>$v){
  			$this->columns['parent_id'][$k]=(int)$v;
  			}
  		}else{
  			$this->columns['parent_id']=(int)$value; 
  		}
  		$this->get_childrens=true;
	}
	
	/**
	 * Setea string de b&uacute;squeda
	 * @param $value string
	 */
  
	function setSearch($value) {
	  	$search=str_replace("%"," ",$value);
	    $search=str_replace("_"," ",$search);
	    $this->search=mysql_real_escape_string(trim($search));
	}
	
	/**
	 * Setea string de b&uacute;squeda excluyente
	 * @param $value
	 */

	function setExclude($value) { $this->exclude=mysql_real_escape_string($value); }
  
	/**
	 * retorna el texto de b&uacute;squeda
	 * @return string
	 */
	
	function getSearch() { return $this->search; }
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#setQuery()
	 */

	protected function setQuery(){
		$search='';
		$query="SELECT $this->table.title, 
		$this->table.description, $this->table.tt, 
		$this->table.approved, $this->table.orig_file, 
		$this->table_video_types.filename,
		$this->table.frame, $this->table_hits.hits AS hits, 
		$this->table.duration, $this->table_videos_categories.categories_id,
		$this->table_categories.title AS categories_title, 
		$this->table_thumbs.filename AS thumb ";
		$query.=", $search
		$this->table.id
		FROM $this->table 
		LEFT JOIN $this->table_thumbs ON $this->table_thumbs.videos_id=$this->table.id 
		LEFT JOIN $this->table_videos_categories ON $this->table.id=$this->table_videos_categories.videos_id 
		LEFT JOIN $this->table_hits ON $this->table.id=$this->table_hits.videos_id 
		LEFT JOIN $this->table_video_types ON $this->table.id=$this->table_video_types.videos_id 
		LEFT JOIN $this->table_categories ON $this->table_categories.id=$this->table_videos_categories.categories_id 
		$this->_where() GROUP BY $this->table.id";
		$this->dataSet->setQuery($query);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#setCountQuery()
	 */
  
	protected function setCountQuery(){
		$query="
		SELECT COUNT(*) 
	    FROM $this->table 
		LEFT JOIN $this->table_categories 
	  	ON $this->table.categories_id=$this->table_categories.id
	   	".$this->_where();
	  	$this->dataSet->setCountQuery($query);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#_where()
	 */
  
	protected function _where(){
  		$where="WHERE 1";
		$ids=$this->idToString("$this->table.id");

		if(@$ids!="")$where.=" AND $ids";
	
		if($this->columns['categories_id']!=0&&!is_array($this->columns['categories_id'])){
			$where.=" AND $this->table.categories_id=".(int)$this->columns['categories_id'];
			
		}elseif(is_array($this->columns['categories_id'])){
			$where.=" AND $this->table.categories_id IN (".implode(',',$this->columns['categories_id']).")";
		}

		if($this->search!="")$where.="
		AND (( ".$this->_tags()." ) OR $this->table.title LIKE '%$this->search%')";
	
		if($this->exclude!=""){
			$tagstr='';
			$tags=explode(" ",trim($this->exclude));
		        foreach($tags as $tag)$tagstr.="$this->table.tags NOT LIKE '%$tag%' AND ";
		        $tagstr=substr($tagstr,0,-4);
			$where.=" AND $tagstr AND $this->table.title NOT LIKE '%$this->exclude%'";
		}
	
		if($this->columns['tt'])$where.=" AND $this->table.tt>".(int)$this->columns['tt'];
		if($this->columns['approved']!==false)$where.=" AND $this->table.approved='".$this->columns['approved']."'";
		if($this->columns['type_id']!==false)$where.=" AND $this->table_video_types.type_id=".(int)$this->columns['type_id'];
		return $where;
	}
	
	/**
	 * Busca tags en string de b&uacute;squeda
	 * @return unknown_type
	 */
  
	private function _tags(){
    	$tagstr='';
		if($this->search!=""){
        	$tags=explode(" ",$this->search);
        	foreach($tags as $tag)$tagstr.="$this->table.tags LIKE '%$tag%' OR ";
       		$tagstr=substr($tagstr,0,-4);
		}
		return $tagstr;
	}
  
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#add()
	 */
	function add(){
	  	/*No deben ser aprobados videos que no existen*/
	  	if(is_null($this->columns['filename'])) $this->columns['approved'] = false;
	
	  	$this->columns['tt']=time();
		$videos_id=parent::add();
		$this->setState('change_immediate');
		$this->notifyObservers();
	  	
	  	if(!empty($this->columns['tags'])){
			$tags=new MTags();
			$tags->setVideos_id($videos_id);
			$this->storeTags($videos_id);
	  	}
		return $videos_id;
  	}

	/**
	 * Almacena tags
	 * @param $videos_id
	 * @return unknown_type
	 */
  	
  	private function storeTags($videos_id){
	    $dao=new DAO();
		$this->tags=explode(",",stripslashes($this->tags));
		$dao->query("DELETE FROM video_tags WHERE videos_id = ".(int)$videos_id);
		
		foreach($this->tags as $tag){
			$tag=trim($tag, ' ,');
	 		$dao->query("SELECT id FROM tags WHERE LOWER(tag)='".trim(mysql_real_escape_string(strtolower($tag)))."'");
	 	  	
			if($dao->rowCount()>0){
		    	$tags_id=$dao->get(0,"id");
			}else{
		    	$dao->query("INSERT INTO tags (tag)VALUES('".mysql_real_escape_string(trim($tag))."')");
		    	$dao->query("SELECT last_insert_id() AS id FROM tags");
		    	$tags_id=$dao->get(0,"id");
			}
		  	if(trim($tag) != 'Array')
		 	$dao->query("INSERT INTO video_tags (videos_id,tags_id)VALUES(".(int)$videos_id.",$tags_id)");
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#update()
	 */
  
	function update(){
		$this->setState('change_immediate');
		$this->notifyObservers();

		if(!empty($this->columns['tags'])){
			$tags=new MTags();
			$tags->setVideos_id($this->id);
			$this->storeTags($this->id);
		}
		
		return parent::update();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see www/classes/models/MModel#delete()
	 */
  
	function delete(){
  		//remover category
  		if($this->columns['categories_id']!=0){
  			$this->id=$this->columns['categories_id'];
  			$ids=$this->idToString("categories_id");
  		}else{
  			$ids=$this->idToString("id");
  		//remover video thumbnails
	  		$thumb=new MThumbs();
	  		$thumb->setVideos_id($this->id);
	  		$thumb->delete();
	  
	  		$thumb=new MImages();
	  		$thumb->setVideos_id($this->id);
	  		$thumb->delete();
  		}

  	//remover archivos del video
  		if($ids!=""){
	  		$where="WHERE $ids";
		  	$dao=new DAO();
		  	
		  	$query="SELECT orig_file, frame FROM $this->table $where";
		  	$dao->query($query);
		  	while($row=$dao->getAll()) {
		  		@unlink(ROOT.FILES."/".$row['orig_file']);
		  		@unlink(ROOT.FILES."/".$row['frame']);
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

	/**
	 * Rankea un video mediante votos
	 * @param $rating integer
	 * @return mysql_result 
	 */  
  
	public function rate($rating){
	  	$dao=new DAO();
	    
	    if(!isset($_COOKIE["rated_$this->id"])){
		    $rate=(int)$rating;
		    $dao->query("INSERT INTO ratings (videos_id,rate)VALUES(".(int)$this->id.",$rate)");
		    setcookie("rated_$this->id",1,time()+2592000);
	    }
	    
	    $dao->query("SELECT AVG(ratings.rate)AS rate FROM ratings WHERE videos_id=".(int)$this->id);
	    $rate=$dao->get(0,"rate");
	    $dao->query("UPDATE $this->table SET rate=$rate WHERE id=".(int)$this->id);
	    
	    $this->setState('change_delayed');
		$this->notifyObservers();
			
	    return $dao->get(0,"rate");
	}
  
	/**
 	* Suma hit o visita
 	*/  
  
	public function view(){
  		if((int)$this->id!=0){
  			$dao=new DAO();
    		$dao->query("INSERT INTO $this->table_hits (videos_id, hits) VALUES (".(int)$this->id.",1) ON DUPLICATE KEY UPDATE hits=hits+1");

		    /*
		    $watched=new MWatched();
		    $watched->setVideos_id($this->id);
		    $watched->add();
		    */
    
    		$this->setState('change_delayed');
			$this->notifyObservers();
  		}	
	}  
}
?>