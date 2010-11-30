<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/UrlBuilder.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/views/VIphoneVideos.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MSimilarVideos.php");
include_once(ROOT."classes/lib/Types.php");

class VPlayer extends VView {
	
  public $model;
  public $next_id;
  public $playlists_id;
  public $id;
  public $info;
  public $username;
  public $filename;
  public $filename_hd;
  public $frame;
  private $params=array();
  
  function VPlayer($model){
    $this->model=$model;
    
    $this->playlists_id='';
    $this->next_id='';
    $this->id='';
  }
  
  function show(){
  	if($this->info==null)$this->info=$this->model->next();
	
  	if(!empty($this->info['code'])){
  		$code=html_entity_decode($this->info['code']);
  	} elseif (!empty($this->info['video_embed'])){
  		$code=html_entity_decode($this->info['video_embed']);
  	}else{
  		$code="There is no player configured to play this video format.";
  	}
  	
  	$tpl=new Template($code);
  	//$tpl->was_adviced='0';
  	$tpl->id=$this->id;
  	$tpl->playlists_id=$this->playlists_id;
	$tpl->next_id=$this->next_id;
  	$tpl->base=urlencode(URL.'/');
  	$tpl->url=URL;
  	$tpl->filename=URL.'/'.FILES.'/'.$this->filename;
  	
  	
  	if(Util::getOS() == 'iPad'){
  		$strSQL = "SELECT videos.filename_hd AS filename FROM videos WHERE id = $this->id LIMIT 0, 1";
	  	$qry = mysql_query($strSQL);
	  	
	  	if($row = mysql_fetch_array($qry)){
	  		$tpl->filename= URL.'/'. FILES .'/'.$row['filename'];
	  	}
  	}
  	if(!isset($row['filename']) || empty($row['filename'])){
	  	if(Util::getOS() == 'iPhone' || Util::getOS() == 'iPod' || Util::getOS() == 'iPad' || Util::getOS() == 'BlackBerry'
	  	&&(ctype_digit($this->id) && !empty($this->id))){
		   	$strSQL = "SELECT videos.small_filename AS filename FROM videos WHERE id = $this->id LIMIT 0, 1";
		  	$qry = mysql_query($strSQL);
		  	
		  	if($row = mysql_fetch_array($qry)){
		  		$tpl->filename= URL.'/'. SMALL_VIDEOS .'/'.$row['filename'];
		  	}
	  	}else{
			$tpl->was_adviced = '0';

			//Obtenemos parametro del embed hd.file
	  		$qry = mysql_query("SELECT frame, filename_hd FROM videos WHERE id =".(int) $this->id . " ");
			if($row = mysql_fetch_assoc($qry)){
				$this->frame=$row['frame'];
				$this->filename_hd=$tpl->filename_hd=$row['filename_hd']; 
			}	
			
	  	}
	}

    foreach($this->params as $key => $value){
  		$tpl->$key = $value;
   	}
	return $tpl->output();
  }
    
  function embed(){
  	if($this->info==null)$this->info=$this->model->next();
  	
  	$embed=isset($this->info['embed'])?html_entity_decode($this->info['embed']):"";
	
  	if(!empty($this->info['embed'])){
  		$embed=html_entity_decode($this->info['embed']);
  	}elseif (!empty($this->info['video_embed'])){
  		$embed=html_entity_decode($this->info['video_embed']);
  	}else{
  		$embed='';
  	}
  	$tpl=new Template($embed);
  	$tpl->base=urlencode(URL.'/');
  	$tpl->url=URL;
  	$tpl->id=$this->id;
	$tpl->frame=URL.'/'.FILES.'/'.$this->frame;
  	$tpl->filename_hd=URL.'/'.FILES.'/'.$this->filename_hd;
  	$tpl->filename=urlencode(URL.'/'.FILES.'/'.$this->filename);

  	foreach($this->params as $key => $value){
  		$tpl->$key = $value;
   	}
 
  	
  	return htmlspecialchars($tpl->output());
  }
  
  /**
   * Buscamos cortina publicitaria asociada si es que existe
   *
   * @param int $videos_id
   * @return id de video asociado o false
   */
  
  function searchAds($videos_id){
	$qry="SELECT video_curtain_ads.videos_id FROM video_curtain_ads 
	LEFT JOIN categories 
	ON video_curtain_ads.categories_id = categories.id
	OR categories.id IN (SELECT id FROM categories WHERE parent_id = video_curtain_ads.categories_id)
	LEFT JOIN videos 
	ON categories.id = videos.categories_id WHERE video_curtain_ads.approved = '1' AND
	videos.id =".(int)$videos_id ." LIMIT 0, 1";

	return ($row=mysql_fetch_assoc(mysql_query($qry))) ? $row['videos_id'] : false;
  }
  
  function setParam($param, $value){
	$this->params[$param] = $value; 	
  }


  /**
   * Buscamos los videos que est�n relacionados por los tags para as� armar lista de reproducci�n
   *
   * @param int $videos_id
   * @return string videos id separados por '-'
   */
  function getSimilars($videos_id, $limit=5, $offset=0){
  	$MSimilarVideos = new MSimilarVideos();
  	$MSimilarVideos->setId($videos_id);
	$MSimilarVideos->setLimit($limit);
	$MSimilarVideos->setStart($offset);
	$MSimilarVideos->load();
	$VSimilarVideos = new VIphoneVideos($MSimilarVideos);
	$strSimilarsId = '';
	while($VSimilarVideos->show()){
		$strSimilarsId .= @$VSimilarVideos->recordset['id'].'-';
	}
	$strSimilarsId = substr($strSimilarsId,0,-1);
	return $strSimilarsId;
  }
}
?>