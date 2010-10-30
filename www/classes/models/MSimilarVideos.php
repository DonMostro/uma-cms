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
	$this->table='videos';
	$this->iphone=false;
	
	/*[TODO] Lo ideal es evitar preguntar por variables de $_REQUEST
	 *y en lugar de eso preguntar por atributos de la clase,
	 *no obstante está es una solución rápida y efectiva para la situación 
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
	  	$dao->query("SELECT tags FROM videos WHERE id=".(int)$this->id);
	  	$this->tags=str_replace(' ',',',$dao->get(0,'tags'));
	  }
	  return $this->tags;
  }
  
  private function getTagsId(){
  	   $sql = "SELECT id FROM video_tags WHERE videos_id=".(int)$this->id;
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
    		SELECT '1' AS orden, videos.id AS videos_id, videos.title, videos.approved, 
			videos.duration, videos.rate, videos.hits, videos.tt,
			thumbs.filename AS thumb, 
			(SELECT COUNT(tags_id) FROM video_tags WHERE tags_id IN
				(SELECT tags_id FROM video_tags WHERE videos_id = ".(int)$this->id.") 
				AND videos_id = videos.id
			) AS relevance  
			FROM videos 
			JOIN video_tags
			  ON video_tags.videos_id=videos.id
			LEFT JOIN tags        
			  ON tags.id = video_tags.tags_id	   
			LEFT JOIN thumbs 
			  ON thumbs.videos_id=videos.id
			WHERE videos.approved='1' 
			AND tags.tag <> ''
			AND tags.tag <> 'Array' 
			AND videos.id <> ".(int)$this->id."
		        AND tags_id IN ( 
		        	SELECT tags_id
		    	        FROM video_tags
			        JOIN videos ON video_tags.videos_id=videos.id
			        WHERE videos.id=".(int)$this->id." 
			)
		   ";
	   $query.=($this->iphone) ? " AND videos.small_filename <> '' AND videos.small_filename IS NOT NULL " : "";
       $query.= " GROUP BY videos_id 
		
    ";
	$this->dataSet->addOrder(new DataOrder("videos_id","DESC"));
	$this->dataSet->addOrder(new DataOrder("relevance","DESC"));
  	$this->dataSet->setQuery($query);

  }
  
  protected function setCountQuery(){
 	$query = "SELECT COUNT(*) FROM(
			  SELECT videos.id AS videos_id 
				FROM videos 
				JOIN video_tags
				  ON video_tags.videos_id=videos.id
				LEFT JOIN tags        
				  ON tags.id = video_tags.tags_id	   
				WHERE videos.approved='1' 
			        AND tags_id IN ( 
			        	SELECT tags_id
			    	        FROM video_tags
				        JOIN videos ON video_tags.videos_id=videos.id
				        WHERE videos.id=".(int)$this->id." 
				)
				AND tags.tag <> ''
				AND tags.tag <> 'Array' 
				AND videos.id <> ".(int)$this->id." ";
		   $query.=($this->iphone) ? " AND videos.small_filename <> '' AND videos.small_filename IS NOT NULL " : "";	

		$query.=") AS tabla
		GROUP BY tabla.videos_id 
	) 
	AS t1";
  	$this->dataSet->setCountQuery($query);
  }
  
}
?>