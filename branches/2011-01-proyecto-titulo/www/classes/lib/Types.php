<?php

include_once("root.php");
include_once(ROOT."classes/models/MSettings.php");

class Type{
	protected $value;
	public function getValue(){ return $this->value; }
}

class FileSize extends Type {
	public function FileSize($value){
		if(strtoupper(substr($value,-1,1))=='M'){
			$this->value=substr($value,0,-1)*1024*1024;
		}else{
			$this->value=$value;
		}
	}
}

class DataOrder extends Type{
	public $field;
	public $dir;
	public function DataOrder($field, $dir="DESC"){
		$this->field=mysql_real_escape_string($field);
		$this->dir=$dir;
		$this->value="$field $dir";
	}
}

class Post{
	private $text;
	private $filter;
	public $remove_links;

	public function Post($text){
		$this->text=$text;
		$settings=MSettings::getInstance();
		$settings->setId('bad_words');
		$settings->load();
		$data=$settings->next();
		$this->filter=$data['value'];
		$settings->setId('remove_links');
		$settings->load();
		$data=$settings->next();
		$this->remove_links=$data['value']=='1'?true:false;
		$this->text=$text;
	}

	public function clean($bbcode=false, $emot=false, $html=false){
		if($this->filter!=''){
			$words=explode(" ",trim($this->filter));
			foreach ($words as $i=>$word)$words[$i]="#\b".trim($word)."\b#i";
			$this->text=preg_replace($words,"****",$this->text);
		}

		if($this->remove_links){
			$this->text=preg_replace('#((http:|www\.)[^\s]+)#i','&lt;link borrado&gt;',$this->text);
		}else{
			//$this->text=preg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $this->text);
		}
		if($emot)$this->_parse_emoticons();
		if($bbcode)$this->text=$this->_parse_bbcode($this->text);
		$this->text=str_replace("\r",'',$this->text);
		$br = $html==true?'<br />':' ';
		$this->text=str_replace("\n",$br,$this->text);
		return $this->text;
	}

	private function _parse_bbcode($text){
		$bbcode=BBCode::getInstance();
		$bbcodes=$bbcode->getBBCodes();
		preg_match_all('#\[(.+?)(?:=\"?(.*?)\"?)?\](.*)\[/\\1\]#is',$text,$matches);
		foreach($matches[1] as $i=>$tag){
			if(!empty($bbcodes[$tag])){
				$tpl=new Template($bbcodes[$tag]);
				$tpl->value=$matches[2][$i];
				if(strpos($matches[3][$i],'[')!==false){
					$tpl->text=$this->_parse_bbcode($matches[3][$i]);
				}else{
					$tpl->text=$matches[3][$i];
				}
				$text=str_replace($matches[0][$i],$tpl->output(),$text);
			}
		}
		return $text;
	}

	private function _parse_emoticons(){	
		$emoticons=new MEmoticons();
		$emoticons->load();
		while($e=$emoticons->next()){
			foreach ($e['shortcuts'] as $alias) {
				$this->text=str_replace($alias,'<img class="inline" title="'.$e['title'].'" alt="'.$e['title'].'" src="'.URL.'/emoticons/'.$e['filename'].'" />',$this->text);
			}
		}
	}
}

class BBCode{
  private $bbcodes;
  public function BBCode(){
  	if(file_exists(ROOT.'xml/bbcodes.xml')){
		$dom=simplexml_load_file(ROOT.'xml/bbcodes.xml');
		$this->bbcodes=array();
		foreach ($dom->bbcode as $bbcode){
			$bbtag=(string)$bbcode->bbtag;
			$tpl=trim((string)$bbcode->template);
			$this->bbcodes[$bbtag]=$tpl;
		}
	}
  }

  public function &getInstance(){
  	static $me;
  	if(!$me) {
  		$me=array(new BBCode());
  	}
  	return $me[0];
  }

  public function &getBBCodes(){
  	return $this->bbcodes;
  }
}

class Size extends Type{
	public function Size($val){
		if($val>1099511627776){
			$this->value=number_format($val/1099511627776,2).' TB';
		}elseif($val>1073741824){
			$this->value=number_format($val/1073741824,2).' GB';
		}elseif($val>1048576){
			$this->value=number_format($val/1048576,2).' MB';
		}elseif($val>1024){
			$this->value=number_format($val/1024,2).' KB';
		}else{
			$this->value=number_format($val,2).' B';
		}
	}
}

class Time extends Type{
	function __construct($val){
		if($val>=946080000){
			$d=number_format($val/946080000,0);
			$pl=substr($d,-1,1)!='1'||substr($d,-2,2)=='11'?'s':'';
			$this->value=$d.' year'.$pl;
		}elseif($val>=2592000){
			$d=number_format($val/2592000,0);
			$pl=substr($d,-1,1)!='1'||substr($d,-2,2)=='11'?'s':'';
			$this->value=$d.' month'.$pl;
		}elseif($val>=86400){
			$d=number_format($val/86400,0);
			$pl=substr($d,-1,1)!='1'||substr($d,-2,2)=='11'?'s':'';
			$this->value=$d.' day'.$pl;
		}elseif($val>=3600){
			$d=number_format($val/3600,0);
			$pl=substr($d,-1,1)!='1'||substr($d,-2,2)=='11'?'s':'';
			$this->value=$d.' hour'.$pl;
		}elseif($val>=60){
			$d=number_format($val/60,0);
			$pl=substr($d,-1,1)!='1'||substr($d,-2,2)=='11'?'s':'';
			$this->value=$d.' minute'.$pl;
		}else{
			$pl=substr($val,-1,1)!='1'||substr($d,-2,2)=='11'?'s':'';
			$this->value=$val.' second'.$pl;
		}
	}
}

class TimeSpan extends Type{
	public function __construct($tt){
	    $diff=floor((time()-$tt)/(3600*24));
	    if($diff<1 && 
	    	( date("G",time())<date("G",$tt) ||
	    		( date("G",time())==date("G",$tt) && date("i",time())<date("i",$tt) ) || 
	    		( date("G",time())==date("G",$tt) && date("i",time())==date("i",$tt) && date("s",time())<date("s",$tt) ) 
	    	) 
	       ){
	    	$diff=1;
	    }

	  	if($diff<1){
		  $out="Hoy, ".strftime("%H:%M",$tt);
		}elseif($diff>=1 && $diff<7){
		  $out=$diff==1?"Ayer":str_replace(array('[:d:]','<#d/>'),$diff,"d&iacute;as atr&aacute;s");
		}elseif($diff>=7 && $diff<30){
		  $n=floor($diff/7);
		  $out=$n==1?"Hace una semana":str_replace(array('[:d:]','<#d/>'),$n,"semanas atr&aacute;s");
		}elseif($diff>=30 && $diff<365){
		  $n=floor($diff/30);
		  $out=$n==1?"Hace un mes":str_replace(array('[:d:]','<#d/>'),$n,"meses atras");
		}else{
		  $n=floor($diff/365);
		  $out=(substr($n,-1,1)=='1'&&substr($n,-2,2)!='11')?str_replace(array('[:d:]','<#d/>'),$n,"un a&ntilde;o atr&aacute;s"):str_replace(array('[:d:]','<#d/>'),$n,"a&ntilde;os atr&aacute;s");
		}
		$this->value=$out;
	}
}

class Tags extends Type{
	public function __construct($value, $limit=0){
		//$tags=preg_split("/[\s,]+/",$value);
		$tags=explode(",", trim($value));
	  	$tags=array_filter($tags);
	  	$parsed=array();
	  	$count = ($limit==0||$limit>count($tags)) ? count($tags) : $limit;
	  	$str="";

	  	for ($i=0; $i<$count; $i++){
	  		if(!empty($tags[$i]) && in_array($tags[$i],$parsed)===false){
	  			$search=urlencode($tags[$i]);
	  			$str.="<a href=\"".URL."/index.php?m=search&amp;search=$search\" onclick=\"this.href='javascript:void(0)'; popup('index.php?m=searchpreview&amp;search=$search')\">".$tags[$i]."</a>, ";
	  			$parsed[]=$tags[$i];
	  		}
	  	}
	  	$this->value=substr($str,0,strlen($str)-2);	
	}
}
/**
 * Clase Util, Funcionalidades Est&aacute;ticas 
 * */
class Util{
	public function LimitText($text, $maxlen, $html = false){
		if (strlen($text) <= $maxlen) return $text;
		else{
		$text = substr($text, 0, $maxlen) . "...";
		return $text; 			  		
		}
	}


	function snippet($text,$length=64,$tail="...") {
		$text = trim($text);
		$txtl = strlen($text);
		if($txtl > $length) {
			for($i=1;$text[$length-$i]!=" ";$i++) {
				if($i == $length) {
					return substr($text,0,$length) . $tail;
				}
			}
		$text = substr($text,0,$length-$i+1) . $tail;
		}
		return $text;
	}
	
	public function cadenaXML($string){
		$string=str_replace('&aacute;','á', $string);
		$string=str_replace('&eacute;','é', $string);
		$string=str_replace('&iacute;','í', $string);
		$string=str_replace('&oacute;','ó', $string);
		$string=str_replace('&uacute;','ú', $string);
		$string=str_replace('&Aacute;','Á', $string);
		$string=str_replace('&Eacute;','É', $string);
		$string=str_replace('&Iacute;','Í', $string);
		$string=str_replace('&Oacute;','Ó', $string);
		$string=str_replace('&Uacute;','Ú', $string);
		$string=str_replace('&ntilde;','ñ', $string);
		$string=str_replace('&Ntilde;','Ñ', $string);
		$string=str_replace('&iquest;','¿', $string);
		$string=str_replace('&iexcl;','¡', $string);    
		$string=str_replace('&amp;','Y', $string);
		$string=str_replace('"',' ', $string);
		$string=str_replace("'"," ", $string);
		$string=str_replace('&quot;','"', $string);
		$string=str_replace('&aquot;','"', $string);
		$string=str_replace('&amp;','Y', $string);
		$string=str_replace('&ccedil;','ç', $string);
		$string=str_replace('&','', $string);
		return $string;
	}

	public function getBrowser() {
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$browsers = array(
			'Opera' => 'Opera',
			'Firefox'=> '(Firebird)|(Firefox)', // Usar expresiones regulares para encontrar el browser
			'Galeon' => 'Galeon',
			'Mozilla'=>'Gecko',
			'MyIE'=>'MyIE',
			'Lynx' => 'Lynx',
			'Chrome' => 'Chrome',
			'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
			'Konqueror'=>'Konqueror',
			'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
			'IE8' => '(MSIE 8\.[0-9]+)',
			'IE7' => '(MSIE 7\.[0-9]+)',
			'IE6' => '(MSIE 6\.[0-9]+)',
			'IE5' => '(MSIE 5\.[0-9]+)',
			'IE4' => '(MSIE 4\.[0-9]+)'
		);

		foreach($browsers as $browser=>$pattern) { // 
	    // Usa expresiones regulares para detectar el browser
			if(preg_match(strtolower($pattern), strtolower($userAgent))) { 
				return $browser; // Lo encontró devuelve el alias del browser
			}
		}

		return 'Desconocid0'; // el browser no está en esta lista
	}
	
	public function getIP() {
	    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    elseif (isset($_SERVER['HTTP_VIA'])) {
	       $ip = $_SERVER['HTTP_VIA'];
	    }
	    elseif (isset($_SERVER['REMOTE_ADDR'])) {
	       $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    else {
	       $ip = "unknown";
	    }
    	return $ip;
	} 
		
	public function getOS(){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$OS = array(
			'iPhone' => 'iPhone',
			'iPod' => 'iPod',
			'iPad' => 'iPad',
			'BlackBerry' => 'BlackBerry',
			'Windows' => 'Windows'
		);
		
		foreach($OS as $OS=>$pattern) { 
			if(@eregi($pattern, $userAgent)) { 
				return $OS; 
			}
		}
		return 'Desconocido';
	}

	/**
	Recibe una ip o un Array de Ips con "*" como comodines, (ejemplo 192.*.*.*)
	@param $ban_ip_range, ip con comodines
	@param $user_ip, ip a comparar
	@return boolean
	*/	

	function matchIpByRange($ban_ip_range, $user_ip){
		$range = str_replace('*','(.*)', $ban_ip_range);
	  	return (preg_match('/'.$range.'/', $user_ip)) ? true : false;
	}
	
	public function getSpanishDate($dia=false, $mes=false, $ano=false){
		if(!$dia) $dia=date("d");
		if(!$mes) $mes=date("m");
		if(!$ano) $ano=date("Y");
		
		if ($mes=="01") $mes="Enero";
		if ($mes=="02") $mes="Febrero";
		if ($mes=="03") $mes="Marzo";
		if ($mes=="04") $mes="Abril";
		if ($mes=="05") $mes="Mayo";
		if ($mes=="06") $mes="Junio";
		if ($mes=="07") $mes="Julio";
		if ($mes=="08") $mes="Agosto";
		if ($mes=="09") $mes="Setiembre";
		if ($mes=="10") $mes="Octubre";
		if ($mes=="11") $mes="Noviembre";
		if ($mes=="12") $mes="Diciembre";
		
	
		$spanishDate = "$dia de $mes de $ano";
		echo $spanishDate;
	}
}
?>
