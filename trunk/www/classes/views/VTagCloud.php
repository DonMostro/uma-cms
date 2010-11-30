<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/lib/PageCtrl.php");
include_once(ROOT."classes/lib/Types.php");

class VTagCloud extends VCollection{
  
  public $tplfile;
  public $tplitemfile;
  public $max;
  public $title;
  public $url;
  public $request_url;

  
  function VTagCloud($model){
  	parent::VCollection($model);
    $this->tplfile="";
    $this->tplitemfile="tag_cloud_item.html";
    $this->url='';
    $lang=Lang::getInstance();
    $this->title=$lang->getText('T_TAGS');
  }  

  function decorate_item($info){ //decorate list item
  
  	//Get list item template and set variables
    $tpl=new Template(ROOT."html/$this->tplitemfile");
    
    $tpl->tag=isset($info["tag"])?$info["tag"]:"";
    $tpl->urltag=isset($info["tag"])?urlencode(html_entity_decode($info["tag"])):"";
    $tpl->url=URL;
    $tpl->request_url=$this->request_url;
    
    if(isset($info["hits"])){
    	$hits=(int)$info['hits'];
    	$max=(int)$info['max'];
    	if($max==0)$max=1;
    	$n=$hits/$max;
    	$tpl->size=8+round(14*$n);
    }else{
    	$tpl->size=8;
    }
    
    return $tpl->output();
  }

  function decorate_list($list){ //decorate list
  	if($this->tplfile!=""){
	
		//Get list template and set variables
		$tpl=new Template(ROOT."html/$this->tplfile");
		$tpl->title=$this->title;
		$tpl->list=$list;
		$tpl->url=$this->url;
		$tpl->curl=$this->url;

		return $tpl->output();
  	}else{
  		return $list;
  	}
  }
}
?>