<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/PageCtrl.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/views/VCollection.php");
include_once(ROOT."classes/views/VVideo.php");

class VComments extends VCollection{
  
  public $title;
  public $trigger;
  public $tplfile;
  public $tplitemfile;
  public $tplformfile;
  public $url;
  public $username;
  public $channel;
  public $video_id;
  public $width;
  public $modify;
  
  function VComments($model){
  	parent::VCollection($model);
    $this->tplfile="comments_list.html";
    $this->tplitemfile="comments_item.html";
    $this->tplformfile="comments_form.html";
    $this->width=445;
  }  

  function decorate_item($info){ //decorate list item
  
  	//Get list item template and set variables
    $tpl=new Template(ROOT."html/$this->tplitemfile");
    $tpl->tt=isset($info["tt"])?gmstrftime("%d/%m/%Y",$info["tt"]):"";
    $tpl->username=isset($info['username'])?$info['username']:"";
    $tpl->url_username=urlencode($tpl->username);
    $this->video_id=isset($info['videos_id'])?$info['videos_id']:"";

    $tpl->videos_id=$this->video_id;
    $tpl->id=isset($info['id'])?$info['id']:"";
    
  	if(isset($info['tt'])){
    	$timespan=new TimeSpan($info['tt']);
    	$tpl->time=$timespan->getValue();
    }
    
    if($this->modify || $info['username']==$this->username || !empty($this->username)&&$this->channel==$this->username){
    	$tpl->enable=$info['approved']!=1?0:1;
    }
    
    $tpl->channel=urlencode($this->channel);
    
    $tpl->disabled=$info['approved']!=1?'disabled':'';
    
    if(isset($info['text'])){
    	$post=new Post($info['text']);
    	//con caracteres no reconocidos no hay paginacion en IE
    	$tpl->text=str_replace('?','?',$post->clean(true,true));
    }
    
  	if(!empty($info['filename'])&&file_exists(ROOT.THUMBNAILS."/".$info['filename'])){
        $tpl->thumb=URL."/".THUMBNAILS."/".$info['filename'];
    }elseif(!empty($info['thumb_file'])&&file_exists(ROOT.THUMBNAILS."/".$info['thumb_file'])){
    	$tpl->thumb=URL."/".THUMBNAILS."/".$info['thumb_file'];
    }else{
        $tpl->thumb='';
    }
    
    $this->trigger=$this->trigger==0?1:0;
    $tpl->trigger=$this->trigger;
    
    return $tpl->output();
  }

  function decorate_list($list){ //decorate list
  	if($this->tplfile!=""){
 	
	  	//Get pagination
	    settype($this->id,'integer');
	
		//Get list template and set variables
		$tpl=&new Template(ROOT."html/$this->tplfile");
		$tpl->curl=$this->url;
		$tpl->username=$this->username;
		//Debug::write('vcomments[69]:'.$this->username);
		
		$tpl->width=$this->width;
		$tpl->h_width=$this->width+20;
		
		$tpl->list=$list;
		//$this->model->setVideos_id($this->video_id);
		$tpl->pagination=PageCtrl::getCtrl($this->model->countAll(),$this->model->getStart(),$this->model->getLimit(),$this->url,true,"comments_box",'comments_');
		$this->_setAds($tpl);
		return $tpl->output();
  	}else{
  		return $list;
  	}
  }
  
  function decorate_form(){
  	if($this->tplformfile!=""){
  		$tpl = &new Template(ROOT."html/$this->tplformfile");
  		
  		$tpl->curl=$this->url;
		$tpl->username=$this->username;
  	 	$tpl->sumando1 = rand(1,20);
		$tpl->sumando2 = rand(1,9);
		$tpl->suma = $tpl->sumando1 + $tpl->sumando2;
		return $tpl->output();
   	}else{
 		
  	}
  }
}

?>