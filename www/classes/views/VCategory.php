<?php
include_once("root.php");
include_once(ROOT."classes/models/MTopCarrusel.php");
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
    $lang=Lang::getInstance();
    $this->title=$lang->getText('T_VIDEOS');
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
 
  	$lang=Lang::getInstance();
    $tpl=new Template(ROOT."html/$this->tplitemfile");
    $tpl->tt=isset($info["tt"])?gmstrftime("%d/%m/%Y",$info["tt"]):"";
    $tpl->rss_time=gmstrftime('%a, %d %b %Y %H:%M:%S GMT',$info["tt"]);
    $tpl->title=isset($info['title'])?$info['title']
    :"";
    $tpl->rss_title=isset($info['title'])?utf8_encode(Util::cadenaXML((($info['title'])))):"";
    $tpl->categories_title=isset($info["categories_title"])?$lang->getText($info["categories_title"]):"";
    $tpl->hits=isset($info['hits'])?$info['hits']:"";
    $tpl->username=isset($info['username'])?$info['username']:"";
    $this->username=$tpl->username;
    $tpl->url_username=urlencode($tpl->username);
    $tpl->filename=!empty($info['filename'])?URL.'/'.FILES.'/'.$info['filename']:'';
    //Contamos cada elemento para saber cuando corresponde insertar cortes en el html
    $tpl->num_comments=!empty($info['num_comments'])?$info['num_comments']:'';
    $this->counter++;
    $tpl->counter = $this->counter;
    
	/*Marca último elemento del despliegue*/
	$this->count_last_element=$this->model->getLimit();
	if($this->count_last_element > $this->model->getSize()) $this->count_last_element=$this->model->getSize();
	$tpl->count_last_element = $this->count_last_element;
	/*Fin Marca */
	
    //$tpl->is_last, $tpl->is_first sabemos si es el primer o último elemento en un bloque
    $tpl->is_last = ($this->counter%$this->cutter == 0 || $this->counter == $this->count_last_element) ? "1" : "0";
    $tpl->is_first = (($this->counter+($this->cutter-1))%$this->cutter == 0) ? "1" : "0";

    if(isset($info['description'])){
	    $post=new Post($info['description']);
	    $tpl->description=str_replace('"',"&quot;",$post->clean());
    }

    //$tpl->rss_description=utf8_encode(Util::cadenaXML($tpl->description));
    //$tpl->short_title=//htmlentities(Util::LimitText(@html_entity_decode($tpl->title,null,'utf-8'), 20),null,'utf-8');
    $tpl->short_title=Util::snippet($tpl->title, 40);
    $tpl->id=isset($info['videos_id'])?$info['videos_id']:"";
    $tpl->rate=isset($info['rate'])?round((float)$info['rate']):0;

    if(isset($info['tt'])){
    	$timespan=new TimeSpan($info['tt']);
    	$tpl->time=$timespan->getValue();
    }

    $tpl->duration=isset($info['duration'])?$info['duration']:"";
    $tpl->private=isset($info['private'])?$info['private']:"";
    $tpl->owner=$this->owner;
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
    if(isset($info['thumb'])&&file_exists(ROOT.THUMBNAILS."/".$info['thumb']))
    	$tpl->thumb=URL."/".THUMBNAILS."/".$info['thumb'];
    else $tpl->thumb=URL."/images/blankthumb.jpg";

 //   $lang=Lang::getInstance();

    if($this->title==''&&isset($info["categories_title"])){
    	$this->title=$tpl->categories_title.' '.$lang->getText('T_VIDEOS');
    }
    $this->cat_counter++;	
	$tpl->cat_counter = $this->cat_counter;
	//echo "<!--$this->cat_counter-->";
	
	
    //if($info["categories_title"] != 'Prueba')
    return $tpl->output();
  }
 
  protected function decorate_list($list){ //decorate list
  	if($this->tplfile!=""){
		
 	  	//Get pagination
	    settype($this->id,'integer');
		//Get list template and set variables
		$tpl=new Template(ROOT."html/$this->tplfile");
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
  			if($tpl->c!=0)$this->url="index.php?m=category&c=".$tpl->c."&sort=$tpl->sort&start=0";
  			elseif($tpl->v!=0)$this->url="index.php?m=category&c=".$VPage->req_c_parent;
  		}
  		
  		$tpl->curl=$this->url;
		$tpl->url=$this->url;
		$tpl->surl=$this->surl;
  		
  		
  		include(ROOT."ads/googleChannels.php");
  		//FIN ETIQUETAS
		
		//BBC Mundo
		$tpl->marca_bbc = ($this->title == 'BBC Mundo Videos') ? '
		<!-- START Nielsen Online SiteCensus V5.3 -->
		<!-- COPYRIGHT 2009 Nielsen Online -->
		<script type="text/javascript">
		 var _rsCI="bbc";
		 var _rsCG="0";
		 var _rsDN="//secure-uk.imrworldwide.com/";
		</script>
		<script type="text/javascript"
		src="//secure-uk.imrworldwide.com/v53.js"></script>
		<noscript>
		 <div><img
		src="//secure-uk.imrworldwide.com/cgi-bin/m?ci=bbc&amp;cg=0&amp;cc=1"
		alt=""/></div>
		</noscript>
		<!-- END Nielsen Online SiteCensus V5.3 -->' :
		'';
		//FIN BBC Mundo
		$tpl->categories_title = $this->categories_title;
		$tpl->aux_title=" de ".$info["categories_title"];
		
		if($this->frontdoor=="1"){
			//var_dump($this);
  	 		$mTopCarrusel = new MTopCarrusel();
  	 		$mTopCarrusel->setCategories_Id($this->categories_id);
  	 		$mTopCarrusel->setApproved("1");
  	 		$mTopCarrusel->setLimit(1);
  	 		$mTopCarrusel->load();
  	 		/* VIphoneVideos genera una vista genérica asociada a un RS, su nombre debiera ser mas genérico
  	 		 */
  	 		$view = new VIphoneVideos($mTopCarrusel);
  	 		$view->show();
  	 		
  	 		$tpl->frame = URL."/".IMG_FILES_TOPVIDEOS."/".$view->recordset["foto"];
  	 		$tpl->title = $view->recordset["title"];
  	 		$tpl->hits = $view->recordset["hits"];
  	 		$tpl->id = $view->recordset["videos_id"];
  	 		$tpl->duration = $view->recordset["duration"];
  		}elseif($this->featured_last){
	  			$mVideos = new MVideos();
  				$mVideos->setCategories_Id($this->categories_id);
  				/*[TODO] deberia crearse y usarse $mVideos->setApproved("1"), por el momento esto funciona automagicamente por URL (index.php)*/
  				$mVideos->setStart(0);
   				$mVideos->setLimit(1);
   				$mVideos->addOrder(new DataOrder("tt","DESC"));
   				$mVideos->load();
   				
   				$view = new VIphoneVideos($mVideos);
  	 			$view->show();
   				
	   			$tpl->frame = URL."/".FILES."/".$view->recordset["frame"];
	  	 		$tpl->title = $view->recordset["title"];
	  	 		$tpl->hits = $view->recordset["hits"];
	  	 		$tpl->id = $view->recordset["videos_id"];
	  	 		$tpl->duration = $view->recordset["duration"];	
  				
  		}elseif($this->featured_moreviewed){
  				$mVideos = new MVideos();
  				$mVideos->setCategories_Id($this->categories_id);
  				/*[TODO] deberia crearse y usarse $mVideos->setApproved("1"), por el momento esto funciona automagicamente por URL (index.php) */
  				$mVideos->setStart(0);
   				$mVideos->setLimit(1);
   				$mVideos->addOrder(new DataOrder("hits","DESC"));
   				$mVideos->load();
   				
   				$view = new VIphoneVideos($mVideos);
  	 			$view->show();
   				
	   			$tpl->frame = URL."/".FILES."/".@$view->recordset["frame"];
	  	 		$tpl->title = $view->recordset["title"];
	  	 		$tpl->hits = $view->recordset["hits"];
	  	 		$tpl->id = $view->recordset["videos_id"];
	  	 		$tpl->duration = $view->recordset["duration"];	
  			
		}
		$tpl->ver_mas=$this->ver_mas;	
	

		//$this->_setAds($tpl);
		return $tpl->output();
  	}else{
  		return $list;
  	}
  }
}
?>