<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MSimilarVideos extends MModel {
  
  private $tags;
	
  function __construct(){
    parent::__construct(new RecordSet());
    
	$this->dataSet->setStart(0);  //Start list from
	$this->dataSet->setLimit(20); //Items per list
	$this->table=TABLE_PREFIX.'videos';
	$this->table_tags=TABLE_PREFIX.'tags';
	$this->table_video_tags=TABLE_PREFIX.'video_tags';
	$this->table_thumbs=TABLE_PREFIX.'thumbs';
	$this->iphone=false;
	
	/*[TODO] Lo ideal es evitar preguntar por variables de $_REQUEST
	 *y en lugar de eso preguntar por atributos de la clase,
	 *no obstante está es una solución rápida y efectiva para esta situación 
	 */
	if(@$_REQUEST['m']=="category" && ctype_digit(@$_REQUEST['c'])){
		$vVideo = new VVideo();
		$vPage = new VPage();
		$vPage->SetAllRequestItems();
		$id=$vVideo->getFeaturedVideoIdByCategory($vPage->req_c);
		$this->setId($id);
	}
	
    
  }
  
  private function getTags(){
	  if(empty($this->tags)){
	  	$dao=new DAO();
	  	$dao->query("SELECT $this->table_tags FROM $this->table WHERE id=".(int)$this->id);
	  	$this->tags=str_replace(' ',',',$dao->get(0,'tags'));
	  }
	  return $this->tags;
  }
  
  private function getTagsId(){
  	   $sql = "SELECT id FROM $this->table_video_tags WHERE videos_id=".(int)$this->id;
	   $qry = mysql_query($sql);
	   $tags_id='';
	   while($row=mysql_fetch_assoc($qry)){
			$tags_id.=$row['id'].',';	   	
	   }
	   $tags_id = substr($tags_id,0,-1);
  	   return $tags_id;		
  }
    
  function setIphone($value=true) {$this->iphone = $value===false ? false : true; }
  protected function setQuery(){
    $query = "
    		SELECT '1' AS orden, $this->table.id AS videos_id, $this->table.title, $this->table.approved, 
			$this->table.duration, $this->table.rate, $this->table.hits, $this->table.tt,
			$this->table_thumbs.filename AS thumb, 
			(SELECT COUNT(tags_id) FROM $this->table_video_tags WHERE tags_id IN
				(SELECT tags_id FROM $this->table_video_tags WHERE videos_id = ".(int)$this->id.") 
				AND videos_id = $this->table.id
			) AS relevance  
			FROM $this->table 
			JOIN $this->table_video_tags
			  ON $this->table_video_tags.videos_id=$this->table.id
			LEFT JOIN $this->table_tags        
			  ON $this->table_tags.id = $this->table_video_tags.tags_id	   
			LEFT JOIN $this->table_thumbs 
			  ON $this->table_thumbs.videos_id=$this->table.id
			WHERE $this->table.approved='1' 
			AND $this->table_tags.tag <> ''
			AND $this->table_tags.tag <> 'Array' 
			AND $this->table.id <> ".(int)$this->id."
		        AND tags_id IN ( 
		        	SELECT tags_id
		    	        FROM $this->table_video_tags
			        JOIN $this->table ON $this->table_video_tags.videos_id=$this->table.id
			        WHERE $this->table.id=".(int)$this->id." 
			)
		   ";
	   $query.=($this->iphone) ? " AND $this->table.small_filename <> '' AND $this->table.small_filename IS NOT NULL " : "";
       $query.= " GROUP BY videos_id 
		
    ";
	$this->dataSet->addOrder(new DataOrder("videos_id","DESC"));
	$this->dataSet->addOrder(new DataOrder("relevance","DESC"));
  	$this->dataSet->setQuery($query);

  }
  
  protected function setCountQuery(){
 	$query = "SELECT COUNT(*) FROM(
			  SELECT $this->table.id AS videos_id 
				FROM $this->table 
				JOIN $this->table_video_tags
				  ON $this->table_video_tags.videos_id=$this->table.id
				LEFT JOIN $this->table_tags        
				  ON $this->table_tags.id = $this->table_video_tags.tags_id	   
				WHERE $this->table.approved='1' 
			        AND tags_id IN ( 
			        	SELECT tags_id
			    	        FROM $this->table_video_tags
				        JOIN $this->table ON $this->table_video_tags.videos_id=$this->table.id
				        WHERE $this->table.id=".(int)$this->id." 
				)
				AND $this->table_tags.tag <> ''
				AND $this->table_tags.tag <> 'Array' 
				AND $this->table.id <> ".(int)$this->id." ";
		   $query.=($this->iphone) ? " AND $this->table.small_filename <> '' AND $this->table.small_filename IS NOT NULL " : "";	

		$query.=") AS tabla
		GROUP BY tabla.videos_id 
	) 
	AS t1";
  	$this->dataSet->setCountQuery($query);
  }
  
}
?>