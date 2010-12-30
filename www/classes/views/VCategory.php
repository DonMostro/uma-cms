<?php
include_once("root.php");
include_once(ROOT."classes/models/MFeaturedVideos.php");
include_once(ROOT."classes/models/MCategories.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/PageCtrl.php");
include_once(ROOT."classes/lib/UrlBuilder.php");
include_once(ROOT."classes/views/VCollection.php");
include_once(ROOT."classes/views/VVideo.php");
include_once(ROOT."classes/lib/Types.php");

class VCategory extends VCollection{

  public $title;
  public $trigger;
  public $tplfile;
  public $tplitemfile;
  public $url;
  public $surl;
  public $featured;
  public $options;
  public $current_id;
  public $showad;
  public $owner;
  public $addto;
  public $group;
  public $username;
  public $description;
  public $visitor;
  public $modify;
  public $subcats;
  public $category;
  public $categories;
  public $tags;
  public $time;
  public $infotags;
  public $counter;
  public $count_current;
  public $cat_counter;
  public $tags_limit;
  public $tags_time_limit;
  
  public $cutter; // Marca que indica el numero de elementos para hacer un corte html(ej: cierre de un div)
  public $frontdoor;
  public $categories_id; 
  public $parent_id;
  public $categories_title;
  
  public $featured_last;
  public $featured_moreviewed;
  
  public $ver_mas;
 
  
  function VCategory($model){
  	//FIN ETIQUETAS
  	$this->tplfile="category_list.html";
  	$this->tplitemfile="category_list_item.html";	
  	parent::__construct($model);

    $this->options="true";
    $this->featured=null;
    $this->showad="true";
    $this->addto='';
    $this->title="Videos";
    $this->username='';
    $this->visitor='';
    $this->description='';
    $this->subcats='';
    $this->category='';
    $this->counter=0;
    $this->cat_counter=0;
    //$this->tags_limit=20;
    //$this->tags_time_limit = 86400 * 4;
  }   

  protected function decorate_item($info){ //decorate list item
 
    $tpl=new Template(ROOT."templates/$this->tplitemfile");
    $tpl->tt=isset($info["tt"])?gmstrftime("%d/%m/%Y",$info["tt"]):"";
    $tpl->rss_time=gmstrftime('%a, %d %b %Y %H:%M:%S GMT',$info["tt"]);
    $tpl->rss_iptv_time=date("c", $info["tt"]);
    $tpl->title=isset($info['title'])?$info['title']
    :"";
    $tpl->rss_title=isset($info['title'])?utf8_encode(Util::cadenaXML((($info['title'])))):"";
    $tpl->categories_title=@$info["categories_title"];
    $tpl->rss_categories_title=isset($info["categories_title"])?utf8_encode(Util::cadenaXML(($info['categories_title']))):"";
    
    //echo $info['categories_id'].$info['categories_title']." ";
   if($parent_categories_title=MCategories::getParent(@$info['categories_id'])){
    	//echo "halo";
    	$tpl->has_parent="1";
	    $tpl->rss_parent_categories_title=utf8_encode(Util::cadenaXML(($parent_categories_title)));
    }
    $tpl->hits=isset($info['hits'])?$info['hits']:"";
    $tpl->username=isset($info['username'])?URL.'/'.FILES.'/'.$info['username']:"";
    $tpl->frame=isset($info['frame'])?$info['frame']:"";
    $this->username=$tpl->username;
    $tpl->url_username=urlencode($tpl->username);
    $tpl->filename=!empty($info['filename'])?URL.'/'.FILES.'/'.$info['filename']:'';

    

      //Contamos cada elemento para saber cuando corresponde insertar cortes en el html

    $this->counter++;
    $tpl->counter = $this->counter;
    
	/*Marca último elemento del despliegue*/
	$this->count_last_element=$this->model->getLimit();
	if($this->count_last_element > $this->model->getSize()) $this->count_last_element=$this->model->getSize();
	$tpl->count_last_element = $this->count_last_element;
	/*Fin Marca */
	
    //$tpl->is_last, $tpl->is_first sabemos si es el primer o último elemento en un bloque
    $tpl->is_last = @($this->counter%$this->cutter == 0 || $this->counter == $this->count_last_element) ? "1" : "0";
    $tpl->is_first = @(($this->counter+($this->cutter-1))%$this->cutter == 0) ? "1" : "0";
 
    if(isset($info['description'])){
	    $post=new Post($info['description']);
	    $tpl->description=str_replace('"',"&quot;",$post->clean());
    }

    $tpl->rss_description=Util::snippet(utf8_encode(Util::cadenaXML(@$tpl->description)),512,'');
    //$tpl->short_title=//htmlentities(Util::LimitText(@html_entity_decode($tpl->title,null,'utf-8'), 20),null,'utf-8');
    $tpl->short_title=Util::snippet($tpl->title, 40);
    $tpl->id=isset($info['id'])?$info['id']:$info['videos_id'];
    $tpl->rate=isset($info['rate'])?round((float)$info['rate']):0;

    if(isset($info['tt'])){
    	$timespan=new TimeSpan($info['tt']);
    	$tpl->time=$timespan->getValue();
    }

    $tpl->duration=isset($info['duration'])?$info['duration']:"";
    $arrMinutes=explode(":",$info['duration']);
    @$seconds=(@$arrMinutes[0]*60)+(@$arrMinutes[1]);
    $tpl->seconds=$seconds;
    $tpl->modify=$this->modify;
    $tpl->disabled=$info['approved']!=1?'disabled':'';
    if($this->modify=='true'){
    	$tpl->enable=$info['approved']!=1?0:1;
    }
    if($this->owner=='')$tpl->owner=$this->visitor==$this->username?'true':'false';
    $tpl->addto=$this->addto;
    $tpl->group=$this->group;

    $url=new UrlBuilder("video");
    $url->addParam(0,preg_replace(array('/&.+?;/','/( |\/)/'),array('_','-'),$tpl->title));
    $url->addParam("v",$tpl->id);

    $tpl->videourl=str_replace('&amp;','&',$url->build());
	
	
/*
    if(isset($info['tags'])){
    	$tags=new Tags($info['tags']);
    	$this->infotags = $info['tags'];
  	//  $tags = VTagCloud::decorate_list($info[$tags]);
  	//	$tag_cloud = new VTagCloud($tags);
    //	$tpl->tags=$tags->getValue();
    }

    $tpl->ord=isset($info['ord'])?$info['ord']:"";
    if($this->current_id==$tpl->id)$tpl->playing="true";
    $this->trigger=$this->trigger==0?1:0;
    $tpl->trigger=$this->trigger;
 */   
    if(isset($info['thumb'])&&file_exists(ROOT.FILES_THUMBNAILS."/".$info['thumb']))
    	$tpl->thumb=URL."/".FILES_THUMBNAILS."/".$info['thumb'];
    else $tpl->thumb=URL."/images/blankthumb.jpg";

 //   $lang=Lang::getInstance();

    if($this->title==''&&isset($info["categories_title"])){
    	$this->title=$tpl->categories_title;
    }
    $tpl->siteurl=URL;
    $this->cat_counter++;	
	$tpl->cat_counter = $this->cat_counter;
	//echo "<!--$this->cat_counter-->";
	
	
    //if($info["categories_title"] != 'Prueba')
    //if(!empty($info["filename_hd"]))
    return $tpl->output();
  }
 
  protected function decorate_list($list){ //decorate list
  	if($this->tplfile!=""){
		
 	  	//Get pagination
	    settype($this->id,'integer');
		//Get list template and set variables
		$tpl=new Template(ROOT."templates/$this->tplfile");
		$tpl->sitename=SITENAME;
		$tpl->siteurl=URL;
	
		$tpl->rss_now=gmstrftime('%a, %d %b %Y %H:%M:%S GMT');
		$tpl->title=$this->title;
		$tpl->sort=$this->model->getOrder();
		if(isset($this->model->username))$tpl->username=$this->model->username;
		$tpl->featured=isset($this->featured)?$this->featured:"";
		$tpl->list=$list;
		$tpl->options=$this->options;
		$tpl->username=$this->username;
		$tpl->description=$this->description;
		$tpl->categories_title=$this->categories_title;
		$tpl->rss_description=utf8_encode(Util::cadenaXML($this->description));
		$tpl->pagination=PageCtrl::getCtrl($this->model->countAll(),$this->model->getStart(),$this->model->getLimit(),$this->url."&amp;sort=".$this->model->getOrder());

		$tpl->showad=$this->showad;
		$tpl->subcats=$this->subcats;
		//$tpl->c=$this->categories_id;

		$tpl->categories=$this->categories;
		//$tpl->tags=$this->tags;
	  	//ETIQUETAS GOOGLE
  		$VPage = new VPage();
  		$VPage->SetAllRequestItems();
  		$tpl->c=$VPage->req_c;

  		if(empty($this->url)){
  			//$mcategorylist = new MCategories();  			
  			if($tpl->c!=0){
  				$this->url="index.php?m=category&c=".$tpl->c."&sort=$tpl->sort&start=0";
  				//$mcategorylist->setId($tpl->c);
  			}
  			if(@$_REQUEST["m"]=="video" && ctype_digit($_REQUEST["v"])){
   				//$this->url="index.php?m=category&c=".$VPage->req_c_parent;
  				//$mcategorylist->setId($VPage->req_c_parent);
  			}
  			//$mcategorylist->load();
  			//$c=$mcategorylist->next();
			//@$tpl->aux_title = " De ".@$c["title"];
			//if(ctype_digit(@$_REQUEST['c'])) @$tpl->aux_title_rss = @$c["title"];
			//unset($mcategorylist);
			//unset($view);
   		}
  		
  		$tpl->curl=$this->url;
		$tpl->url=$this->url;
		$tpl->surl=$this->surl;

		
		$tpl->categories_title = $this->categories_title;
		
		if($this->frontdoor=="1"){
			//var_dump($this);
  	 		$mfeatured = new MFeaturedVideos();
  	 		$mfeatured->setCategoriesId($this->categories_id);
  	 		$mfeatured->setApproved("1");
  	 		$mfeatured->setLimit(1);
  	 		$mfeatured->load();

  	 		$m=$mfeatured->next();
  	 		
  	 		@$tpl->frame = URL."/".FILES."/".$m["frame"];
  	 		@$tpl->title = $m["title"];
  	 		@$tpl->hits = $m["hits"];
  	 		@$tpl->id = !empty($m["videos_id"]) ? $m["videos_id"] : $m["id"];
  	 		@$tpl->duration = $m["duration"];
  		}elseif($this->featured_last){
	  			$mvideos = new MVideos();
  				$mvideos->setCategories_Id($this->categories_id);
  				/*[TODO] deberia crearse y usarse $mVideos->setApproved("1"), por el momento esto funciona automagicamente por URL (index.php)*/
  				$mvideos->setStart(0);
   				$mvideos->setLimit(1);
   				$mvideos->addOrder(new DataOrder("tt","DESC"));
   				$mvideos->load();
   				
   				$m=$mvideos->next();
			
   				
	   			$tpl->frame = URL."/".FILES."/".$m["frame"];
	  	 		$tpl->title = $m["title"];
	  	 		$tpl->hits = $m["hits"];
	  	 		$tpl->id = !empty($m["videos_id"]) ? $view->recordset["videos_id"] : $m["id"];
	  	 		$tpl->duration = $m["duration"];	
  				
  		}elseif($this->featured_moreviewed){
  				$mVideos = new MVideos();
  				$mVideos->setCategories_Id($this->categories_id);
  				/*[TODO] deberia crearse y usarse $mVideos->setApproved("1"), por el momento esto funciona automagicamente por URL (index.php) */
  				$mVideos->setStart(0);
   				$mVideos->setLimit(1);
   				$mVideos->addOrder(new DataOrder("hits","DESC"));
   				$mVideos->load();
   				
   				$m=$mVideos->next();
   				
	   			$tpl->frame = URL."/".FILES."/".@$m["frame"];
	  	 		$tpl->title = $m["title"];
	  	 		$tpl->hits = $m["hits"];
	  	 		$tpl->id = !empty($m["videos_id"]) ? $m["videos_id"] : $m["id"];
	  	 		$tpl->duration = $m["duration"];	
  			
		}
		$tpl->ver_mas=$this->ver_mas;	
	

		//$this->_setAds($tpl);
		if($this->model->getSize()>0) return $tpl->output();
  	}else{
  		return $list;
  	}
  }
}
?>
