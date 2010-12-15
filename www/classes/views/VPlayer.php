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
	$VSimilarVideos = new VView($MSimilarVideos);
	$strSimilarsId = '';
	while($VSimilarVideos->show()){
		$strSimilarsId .= @$VSimilarVideos->recordset['id'].'-';
	}
	$strSimilarsId = substr($strSimilarsId,0,-1);
	return $strSimilarsId;
  }
}
?>