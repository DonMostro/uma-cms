<?php
include_once("root.php");
include_once(ROOT."classes/lib/UrlBuilder.php");
include_once(ROOT."classes/views/VCollection.php");
include_once(ROOT."classes/views/VCategory.php");
include_once(ROOT."classes/models/MVideos.php");

class VCategoryList extends VCollection{
  
  public $id;
  public $modify;
  public $topvideos;
  public $counter;
	
  function __construct(IModel $model){
  	$this->tplfile='cat_list.html';
	$this->tplitemfile='cat_list_item.html';
	parent::__construct($model);
  }

  protected function filter(Template $tpl){
	
  	$lang=Lang::getInstance();
    $tpl->title=$lang->getText($tpl->title);
    
	$url=new UrlBuilder("category");
	$url->addParam(0,preg_replace(array('/&.+?;/','/( |\/)/'),array('_','-'),$tpl->title));
    $url->addParam("c",$tpl->id);
    $tpl->caturl=$url->build();
    
    if($this->id==$tpl->id)$tpl->selected='selected="selected"';
    else $tpl->selected='';
    
    $tpl->modify=$this->modify;
    
    /* Top videos. This here is rude breaking of the rules. No View should ever be hard-coupled with a Model. 
       Alas, this is the only reasonable solution of the problem in the current setup. */
	$video=new MVideos();
	$children=array_filter(array($tpl->id)+explode(',',$tpl->children));
	$video->setCategories_Id($children);
	$video->addOrder(new DataOrder('hits','DESC'));
	$video->setLimit(4);
	$video->load();
	$videos=new VCategory($video);
	$videos->tplfile='';
    $this->counter++;
    $tpl->counter = $this->counter;
    $tpl->corte = ($this->counter%4 == 0) ? "1" : "0";
	$videos->tplitemfile='small_item.html';
	$tpl->topvideos=$videos->show();
	
	if(isset($tpl->level)){
		$level=$tpl->level;
		$tpl->level='';
		for($i=0; $i<$level; $i++)$tpl->level.='&nbsp; &nbsp; ';
	}


	if(!empty($tpl->thumb)&&file_exists(ROOT."thumbnails/".$tpl->thumb)){
		$tpl->thumb=URL."/thumbnails/".$tpl->thumb;
	}else{
		$tpl->thumb=URL."/images/folder.gif";
	}
  }

}
?>