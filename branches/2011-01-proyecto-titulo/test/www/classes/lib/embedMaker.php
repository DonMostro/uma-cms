<?
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/views/VIphoneVideos.php");

class embedMaker {

	private $id;
	private $ads;
	private $xml;
	private $image_path;
	private $width;
	private $height;
	private $skin;
	private $hd_state;
	private $controlbar;
	private $swfobject;
	private $streamer;
	private $file;
	private $stream;
	private $stringHTML="";
	//private $player_site;
	//private $player;
	private $local_swf_path;
	private $siteresource;

	public function embedMaker($id, $streamer='', $file='', $ads=0, $xml=0, $image_path='', $width="510", $height="360", $skin='', $controlbar='', $hd_state='', $swfobject=0, $stream=0, $player_site=0){
		$this->id=$id;//en MVideos es id
		$this->ads=($ads==1)?true:false;
		$this->xml=($xml==1)?true:false;
		$this->image_path=$image_path;
		$this->width=$width;
		$this->height=$height;
		
		$this->skin=(!empty($skin))?$skin:false;
		$this->streamer=(!empty($streamer))?$streamer:false;
		$this->file=$file;

		$this->controlbar=(!empty($controlbar))?$controlbar:false;
		$this->hd_state=($hd_state==1)?true:false;
		$this->stream=($stream==1)?true:false;
		
		$this->swfobject=$swfobject;
		if($player_site=="0"){
			//$this->player_site="3tv";
			$this->local_swf_path="http://www.3tv.cl";
			$this->siteresource="resource.latercera.com";
		}
		elseif($player_site=="1"){
			//$this->player_site="latercera";
			$this->local_swf_path="http://resource.latercera.com/swf/player";
			$this->siteresource="resource.latercera.com";
		}
		elseif($player_site=="2"){
			//$this->player_site="lacuarta";
			$this->local_swf_path="http://resource.lacuarta.com/swf/player";
			$this->siteresource="resource.lacuarta.com";
		}
		else{
			$this->local_swf_path="http://www.3tv.cl";
			$this->siteresource="resource.latercera.com";
		}

		
		
		//return ($this->swfobject!=0) ? $this->getSwfObject() : $this->getEmbed();
	}
	
	private function setDao(){
		DAO::connect();
		$model=new MVideos;
		$model->setId($this->id);
		$model->load();
		$view=new VIphoneVideos($model);
		$view->show();
		//if(empty($view->recorset['filename']))exit("No se encontro archivo de video asociado a la id");
		return $view->recordset;
	}
	
	
	public function getEmbed(){
		$view = $this->setDao();
		//Object
		$this->stringHTML 
		.="<object id=\"player_$this->id\"  classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" name=\"player_$this->id\" width=\"$this->width\" height=\"$this->height\">";
		
		$this->stringHTML.="<param name=\"movie\" value=\"$this->local_swf_path/player.swf\" />";
		
		$this->stringHTML.="<param name=\"allowfullscreen\" value=\"true\" />";
		$this->stringHTML.="<param name=\"allowscriptaccess\" value=\"always\" />";
		$this->stringHTML.="<param name=\"wmode\" value=\"opaque\">";
		$this->stringHTML.="<param name=\"flashvars\" value=\"";
		
		if($this->stream)
			$this->stringHTML.="file=".$this->file."&streamer=".$this->streamer."&type=rtmp";
		elseif($this->xml)
			$this->stringHTML.="file=".URL."/index.php?m=filename%26id=".$this->id;
		else
			$this->stringHTML.="file=".URL."/".FILES."/".$view['filename'];
			
					
		
		if($this->image_path)
			$this->stringHTML.="&image=".$this->image_path;
		elseif(!$this->streamer)
			$this->stringHTML.="&image=".URL."/".FILES."/".$view['frame'];
		
		$this->stringHTML.="&plugins=";
				
		if($this->ads)
			$this->stringHTML.="http://adserver.latercera.com/modules/video/v2.0/jwplayer/AdVantage4JWPlayer_v45.swf,";	 
		if(!empty($view['filename_hd']))	
			$this->stringHTML.=$this->local_swf_path."/plugins/hd.swf,";
		
		$this->stringHTML.="googlytics-1";
		
		if($this->skin)
			$this->stringHTML.="&skin=$this->local_swf_path/skins/$this->skin";
		if(!empty($view['filename_hd']))		
			$this->stringHTML.="&hd.file=".URL."/".FILES."/".$view['filename_hd'];
			
		if(!$this->stream){	
			if($this->hd_state)	
				$this->stringHTML.="&hd.state=true";
			else	
				$this->stringHTML.="&hd.state=false";
		}		
			
		if($this->controlbar)	
			$this->stringHTML.="&controlbar=".$this->controlbar;

			
		if($this->ads)
			$this->stringHTML.="&advantage4jwplayer.pluginurl=http://adserver.latercera.com/modules/AdVantageVideo.swf";	
		
		$this->stringHTML.="&repeat=list\"";
		
		$this->stringHTML.="/>";
		
		//Embed
		$this->stringHTML.="<embed type=\"application/x-shockwave-flash\" id=\"player2_$this->id\" name=\"player2_$this->id\" src=\"$this->local_swf_path/player.swf\" width=\"$this->width\" height=\"$this->height\"";
		$this->stringHTML.=" allowscriptaccess=\"always\" allowfullscreen=\"true\" wmode=\"opaque\" flashvars=\"";
		
		if($this->stream)
			$this->stringHTML.="file=".$this->file."&streamer=".$this->streamer."&type=rtmp";
		elseif($this->xml)
			$this->stringHTML.="file=".URL."/index.php?m=filename%26id=".$this->id;
		else
			$this->stringHTML.="file=".URL."/".FILES."/".$view['filename'];	
		
				
		if($this->image_path)
			$this->stringHTML.="&image=".$this->image_path;
		elseif(!$this->stream)
			$this->stringHTML.="&image=".URL."/".FILES."/".$view['frame'];
			
		$this->stringHTML.="&plugins=";	
		
		if($this->ads)
			$this->stringHTML.="http://adserver.latercera.com/modules/video/v2.0/jwplayer/AdVantage4JWPlayer_v45.swf,";	 
		if(!empty($view['filename_hd']))	
			$this->stringHTML.=$this->local_swf_path."/plugins/hd.swf,";
			
		$this->stringHTML.="googlytics-1";
		
		
		if($this->skin)
			$this->stringHTML.="&skin=$this->local_swf_path/skins/$this->skin";
		if(!empty($view['filename_hd']))		
			$this->stringHTML.="&hd.file=".URL."/".FILES."/".$view['filename_hd'];
			
		if($this->hd_state)	
			$this->stringHTML.="&hd.state=true";
		else	
			$this->stringHTML.="&hd.state=false";
	
			
		if($this->controlbar)	
			$this->stringHTML.="&controlbar=".$this->controlbar;

			
		if($this->ads)
			$this->stringHTML.="&advantage4jwplayer.pluginurl=http://adserver.latercera.com/modules/AdVantageVideo.swf";
		
		$this->stringHTML.="&repeat=list\"";
		$this->stringHTML.="/></object>";	
		
		
		return $this->stringHTML; 
	}
	
	public function getSwfObject(){
		$view = $this->setDao();
		if($this->swfobject==2){
			$this->stringHTML.="<script type=\"text/javascript\" src=\"http://$this->siteresource/js/framework/source/prototype-1.6.0.3.js\"></script>\n";
			$this->stringHTML.="<script type=\"text/javascript\" src=\"http://$this->siteresource/js/api/source/UserAgentDetection.js\"></script>\n";			
			$this->stringHTML.="<script type=\"text/javascript\" src=\"http://$this->siteresource/js/framework/swfobject-2.2-min.js\"></script>\n";
		}	
		
		$this->stringHTML.="<div id=\"player$this->id\"></div>\n";
		$this->stringHTML.="<script type=\"text/javascript\">\n";
		
		/**
		*Deteccion iPhone o iPod
		*_quad == Canela.UserAgentDetection
		*/
		
		$this->stringHTML.="if(_cuad.isIphone() || _cuad.isIpod() || _cuad.isIpad()){\n";
		$this->stringHTML.="	var stringHTML = '<object codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\" width=\"$this->width\" height=\"$this->height\" classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\">'\n";
		$this->stringHTML.="	if(_cuad.isIpad()){\n"; 
    	$this->stringHTML.="		stringHTML +='<param name=\"src\" value=\"".URL."/".FILES."/".$view['filename_hd']."\">'\n";
		$this->stringHTML.="	}else{\n";
		$this->stringHTML.="		stringHTML +='<param name=\"src\" value=\"".URL."/".SMALL_VIDEOS."/".$view['small_filename']."\">'\n";					
		$this->stringHTML.="	}\n";  
		
		$this->stringHTML.="	stringHTML +='<param name=\"autoplay\" value=\"true\">'\n";
		$this->stringHTML.="	stringHTML +='<param name=\"loop\" value=\"false\">'\n";
		$this->stringHTML.="	stringHTML +='<param name=\"controller\" value=\"true\">'\n";
		$this->stringHTML.="	stringHTML +='<param name=\"bgcolor\" value=\"black\">'\n";
		$this->stringHTML.="	if(_cuad.isIpad()){\n"; 
    	$this->stringHTML.="	stringHTML +='<embed src=\"".URL."/".FILES."/".$view['filename_hd']."\" pluginspage=\"http://www.apple.com/quicktime/download/\" width=\"$this->width\" height=\"".$this->height."\" autoplay=\"true\" loop=\"false\" controller=\"true\" bgcolor=\"black\"></embed>'\n"; 
		$this->stringHTML.="	}else{\n";
    	$this->stringHTML.="	stringHTML +='<embed src=\"".URL."/".SMALL_VIDEOS."/".$view['small_filename']."\" pluginspage=\"http://www.apple.com/quicktime/download/\" width=\"$this->width\" height=\"".$this->height."\" autoplay=\"true\" loop=\"false\" controller=\"true\" bgcolor=\"black\"></embed>'\n"; 		
		$this->stringHTML.="	}\n";  
    	$this->stringHTML.="	stringHTML +='</object>'\n";
		$this->stringHTML.="	document.getElementById('player$this->id').innerHTML = stringHTML;\n";
		$this->stringHTML.="}else{\n";
		
		
		/**
		*Flash Standard
		*/
		
		
		$this->stringHTML.="	var flashvars={\n";
		$this->stringHTML.="		'height':'$this->height',\n";
		$this->stringHTML.="		'width':'$this->width',\n";
		if($this->stream){
			$this->stringHTML.="		'file':'$this->file',\n";
			$this->stringHTML.="		'streamer':'$this->streamer',\n";
			$this->stringHTML.="		'type':'rtmp',\n";
		}elseif($this->xml)
			$this->stringHTML.="		'file':'".URL."/index.php?m=filename%26id=$this->id',\n";
		else
			$this->stringHTML.="		'file':'".URL."/".FILES."/".$view['filename']."',\n";
		if($this->image_path)
			$this->stringHTML.="		'image':'$this->image_path',\n";
		elseif(!$this->streamer)
			$this->stringHTML.="		'image':'".URL."/".FILES."/".$view['frame']."',\n";
		$this->stringHTML.="		'plugins':'";
				
		if($this->ads)
			$this->stringHTML.="http://adserver.latercera.com/modules/video/v2.0/jwplayer/AdVantage4JWPlayer_v45.swf,";	 
		if(!empty($view['filename_hd']))	
			$this->stringHTML.=$this->local_swf_path."/plugins/hd.swf,";
		
		$this->stringHTML.="googlytics-1',\n";
		
		if($this->skin)
			$this->stringHTML.="		'skin':'$this->local_swf_path/skins/$this->skin',\n";
		if(!empty($view['filename_hd']))		
			$this->stringHTML.="		'hd.file':'".URL."/".FILES."/".$view['filename_hd']."',\n";
		
		if(!$this->stream){	
			if($this->hd_state)	
				$this->stringHTML.="		'hd.state':'true',\n";
			else	
				$this->stringHTML.="		'hd.state':'false',\n";	
		}
		
		if($this->controlbar)	
			$this->stringHTML.="		'controlbar':'$this->controlbar',\n";
			
		if($this->ads)
			$this->stringHTML.="		'advantage4jwplayer.pluginurl':'http://adserver.latercera.com/modules/AdVantageVideo.swf',\n";	
		
		$this->stringHTML.="		'repeat':'list'\n";	
		$this->stringHTML.="	};\n";	
			
			
		$this->stringHTML.="	var params={\n";
		$this->stringHTML.="		'allowscriptaccess':'always',\n";
		$this->stringHTML.="		'allowfullscreen':'true',\n";
		$this->stringHTML.="	};\n";	
		

		$this->stringHTML.="	var attributes={\n";
		$this->stringHTML.="		'id':'player_$this->id',\n";
		$this->stringHTML.="		'name':'player_$this->id'\n";

		$this->stringHTML.="	};\n";	
		

		$this->stringHTML.="	swfobject.embedSWF('$this->local_swf_path/player.swf', 'player$this->id', '$this->width', '$this->height', '9.0.0','false', flashvars, params, attributes);\n";
		
		$this->stringHTML.="}\n";
		
		$this->stringHTML.="</script>";
		return $this->stringHTML; 
				
	}
}
?>