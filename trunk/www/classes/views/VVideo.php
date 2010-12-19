<?php
error_reporting(E_ALL);
include_once("root.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/UrlBuilder.php");
include_once(ROOT."classes/views/VPage.php");
include_once(ROOT."classes/models/MPlayers.php");
include_once(ROOT."classes/models/MComments.php");
include_once(ROOT."classes/views/VPlayer.php");


include_once(ROOT."geoip/geoip.php");

class VVideo extends VView {

  private $model;
  private $form;
  private $user;
  private $lang;
  
  public $categories_title;
  public $tplfile;
  public $comments;
  public $playlists;
  public $next_id;
  public $prev_id;
  public $next_thumb;
  public $prev_thumb;
  public $playlist;
  public $playlists_id;
  public $player;
  public $embed;
  public $favorites_enabled;
  public $playlists_enabled;
  public $ratings_enabled;
  public $taf_enabled;
  public $embed_enabled;
  public $title;
  public $description;
  public $tags;
  public $buffer;
  public $autotemplate;
  public $thumbs;
  public $owner;
  public $curtain_filename;
  public $has_curtain;
  public $show_comments;
  public $categories_id;
  public $show_link;
  public $from_carrusel;

  function VVideo($model=null){
  	parent::VView();
    $this->model=$model;
    $this->tplfile="video_box.html";
    $this->description='';
    $this->tags='';
    $this->autotemplate=1;
  }

  public function show(){
    $lang=Lang::getInstance();
  	if($this->model->getSize()>0){
	    $info=$this->model->next();
	    $this->categories_title=isset($info["categories_title"])?$info["categories_title"]:"";
	    if(!empty($info['template'])&&$this->autotemplate){
	    	$this->tplfile=$info['template'];
	    }
	    //if(isset($this->videos_id)) $this->id = $this->videos->id;
	    //load template and set variables
	    $tpl=new Template(ROOT."html/$this->tplfile");
	    $tpl->id=isset($info['id'])?$info['id']:"";
	    $tpl->categories_id=isset($info['categories_id'])?$info['categories_id']:"";
	    $tpl->host=$_SERVER['HTTP_HOST'];
	    $this->title=isset($info['title'])?$info['title']:"";
	    $tpl->title=$this->title;
	    $VPage = new VPage();
	    $gi = geoip_open(ROOT."geoip/GeoIP.dat",GEOIP_STANDARD);
	    $isInternal = (Util::matchIpByRange("192.168.*.*", $_SERVER['REMOTE_ADDR']) || Util::matchIpByRange("172.16.*.*", $_SERVER['REMOTE_ADDR']));
	    $tpl->restricted = 
	    (geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']) != "CL" && !$isInternal)
	    	&& $this->categories_title == "Rumbo al Mundial"
	    	? "true" : "false";

	    if(isset($info['description'])){
		    $post=new Post($info['description']);
		    $post->remove_links = false;
		    $tpl->description=$post->clean(false, false, false);
		    $post=new Post($info['description']);
		    $post->remove_links = true;
		    $this->description=$post->clean(false, false, false);
	    }
		
	    
	    $tpl->categories_title=$this->categories_title;
	    $tpl->rate=isset($info['rate'])?(int)$info['rate']:0;
	    $tpl->hits=isset($info['hits'])?$info['hits']:0;
	    $tpl->username=isset($info['username'])?$info['username']:"";
	    $tpl->url_username=urlencode($tpl->username);

	  	if(isset($info['tt'])){
	    	$timespan=new TimeSpan($info['tt']);
	    	$tpl->time=$timespan->getValue();
	    }

	    if(empty($info['filename'])&&!empty($info['orig_file']))$info['filename']=$info['orig_file'];
	    if(!empty($info['filename'])){
	    	if(empty($info['server'])||$info['server']=='localhost'){
	    		$tpl->filename=URL.'/'.FILES.'/'.$info['filename'];
	    	}else{
	    		$tpl->filename=rtrim($info['url'], '/').'/'.$info['filename'];
	    	}
	    }else{
	    	$tpl->filename='';
	    }
		$tpl->num_comments=!empty($info['num_comments'])?$info['num_comments']:'';
		$tpl->publicidad='';
	    $VPage = new VPage();
		$VPage->SetAllRequestItems();
		include(ROOT."ads/adTechChannels.php");
		
		
		$tpl->canal = $VPage->req_c;
		
		//Goles
		$tpl->gadsense_comentarios = ($VPage->req_c_parent == C_GOLES) ? '':
		'
		<script language="JavaScript">
		<!--
		google_ad_client = \'pub-9694759232015294\'; // sustituya su c�digo id_cliente (pub-#)
		google_language = \'es\';
		google_ad_slot = \'3093783667\';
		google_ad_output = \'js\';
		google_max_num_ads = \'3\';
		google_ad_type = \'text\';
		google_feedback = \'on\';
		// -->
		</script>
		<div class="comments-ads">
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
		</div>
		';
		
		//BBC Mundo
		$tpl->marca_bbc = (@$info["categories_id"] == C_BBC_MUNDO ) ? '
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
		<!-- END Nielsen Online SiteCensus V5.3 -->
		' :
		'';
		//FIN BBC Mundo
		
	
		
		$tpl->is_test_page = ($this->categories_title == 'Prueba') ? "1" : "0";
		
	    $tpl->link=isset($info['link'])?htmlentities($info['link']):"";
	    $tpl->link_title=isset($info['link_title'])?htmlentities($info['link_title']):"";
		$tpl->duration=isset($info['duration'])?$info['duration']:"";
		$tpl->frame=isset($info['frame'])? URL ."/". FILES ."/". $info['frame']:"";
		
		$tpl->embed=$this->embed;
		
	    $tpl->next_id=$this->next_id;
	    $tpl->prev_id=$this->prev_id;
	    $tpl->qua=(@$_REQUEST['qua']=='hd') ? 'hd' : ''; 
	    $tpl->playlists_id=$this->playlists_id;
	    $tpl->playlist=$this->playlist;
	    $tpl->showlink=$this->show_link;
	    
	    $VPage = new VPage();
  		$VPage->SetAllRequestItems();
	    
	    /*if(!empty($this->categories_id)){
	    	echo $this->categories_id;
			$this->getFeaturedVideoId($this->categories_id);	    	
	    }
	    */
  		//echo $tpl->id;
  		
	    /*Player*/
	    
		$mplayer=new MPlayers();
		if(empty($info['filename_hd'])) $mplayer->setType("Flash Video Player NO HD");
		$player=new VPlayer($mplayer);
		
		
		//$mplayer->setVideo_Id($tpl->id);
		
		$mplayer->load();
		$player->id=(!$this->from_carrusel)? $tpl->id : $info["videos_id"];
		if(@$_GET["m"]=="video"){
			$player->setParam("autostart", "true");
		}else{
			$player->setParam("autostart", "false");
		}	
	    $tpl->player=$player->show();
	    
	    
	    /*End Player*/
	    
	    $tpl->favorites_enabled=$this->favorites_enabled?"true":"false";
	    $tpl->playlists_enabled=$this->playlists_enabled?"true":"false";
	    $tpl->ratings_enabled=$this->ratings_enabled?"true":"false";
	    $tpl->taf_enabled=$this->taf_enabled?"true":"false";
	    $tpl->embed_enabled=($this->embed_enabled && $tpl->categories_id != C_CAMPEONATO_CHILENO)?"true":"false";
	    $url=new UrlBuilder("video");
   		$url->addParam(0,preg_replace(array('/&.+?;/','/( |\/)/'),array('_','-'),$tpl->title));
    	$url->addParam("v",$tpl->id);
    	$tpl->videourl=$url->build(false);
    	$tpl->videourl_enc=urlencode($tpl->videourl);
 	    $tpl->buffer=$this->buffer;
	    $tpl->thumbs=$this->thumbs;
	    $tpl->owner=$this->owner;
	    

	    

	    if(isset($this->next_thumb)&&file_exists(ROOT.THUMBNAILS."/".$this->next_thumb))
	    	$tpl->next_thumb=URL."/".THUMBNAILS."/".$this->next_thumb;
	    else $tpl->next_thumb=URL."/images/blankthumb.jpg";
	    if(isset($this->prev_thumb)&&file_exists(ROOT.THUMBNAILS."/".$this->prev_thumb))
	    	$tpl->prev_thumb=URL."/".THUMBNAILS."/".$this->prev_thumb;
	    else $tpl->prev_thumb=URL."/images/blankthumb.jpg";
	    $tpl->downloadable=isset($info['downloadable'])&&$info['downloadable']=='1'?'true':'';

	    if(!empty($info['size'])){
			$size=new Size($info['size']);
			$tpl->size=$size->getValue();
		}else{
			$tpl->size='0 B';
		}
		
		$tpl->facebook = urlencode(htmlentities("/index.php?m=video&v=$tpl->id"))."&t=".str_replace('"',"'",$tpl->title);
		$tpl->delicious = urlencode(htmlentities("/index.php?m=video&v=$tpl->id"));
		$tpl->myyahoo = urlencode(htmlentities("/index.php?m=categoryrss&c=$tpl->categories_id"));
		$tpl->igoogle = urlencode(htmlentities("/index.php?m=categoryrss&c=$tpl->categories_id"));
		$tpl->show_comments=$this->show_comments;
		
  		//Comments
  		$this->form=Form::getInstance();
  		$this->user=CUser::getInstance();
  		$this->lang=Lang::getInstance();
		
		$acl=new ACL($this->user->username,'comments');
		if($acl->canAccess()){
			$commentsmodel=new MComments();
			
			if(isset($this->form->comments_start))$commentsmodel->setStart($this->form->comments_start);
			$commentsmodel->setLimit(5);
			$commentsmodel->setVideos_Id($tpl->id);
			
			$comments=new VComments($commentsmodel);
			
			if($acl->canModify()){
				$comments->modify=true;
			}else{
				$commentsmodel->setApproved(1);
			}
			
			$commentsmodel->load();
			
			$comments->username=$this->user->username;
			$comments->url="index.php?m=comments&amp;v=".$tpl->id;
			$comments->empty_msg=$this->lang->getText('M_FIRST_COMMENT');
			if($this->user->username=="")$comments->empty_msg.=' '.$this->lang->getText('M_SIGNIN_COMMENT');
			$this->comments=$comments->show();
		} else {
			$this->comments="";
		}
	
		
		
	    $tpl->comments=$this->comments;
	    $tpl->playlists=$this->playlists;
	    



	    //parse tags
	  	if(isset($info['tags'])){
	    	$tags=new Tags($info['tags']);
	    	$tpl->tags=$tags->getValue();
	    }
		

	    $this->tags=isset($info['tags'])?$info['tags']:'';
		$tpl->curtain_filename=$this->curtain_filename;
		$tpl->has_curtain=$this->has_curtain;

		
		
	    //$this->_setAds($tpl);
		//var_dump($this);
		$tpl->group=AD_UNIQUE_NUMBER;
	    return $tpl->output();
  	}else{
  		return $lang->getText('E_SUSPENDED_VIDEO');
  	}
  }
}
?>