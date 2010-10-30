<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/models/MText.php");
include_once(ROOT."classes/lib/Settings.php");

class Lang {
  
  private $cache;
  private $model;
  private $code;
	
  function Lang(){
  	$s=Settings::getInstance();
  	$settings=$s->getSettings();
  	
    $this->code=isset($_COOKIE['lang'])?$_COOKIE['lang']:$settings['default_language'];
    
    $this->model=new MText();
    $this->cache=array();

  }
  
  function &getInstance(){
  	static $me;
  	if(!$me) {
  		$me=array(new Lang());
  	}
  	return $me[0];
  }
  
  function getText($id){ 
  	if(key_exists($id,$this->cache)){
  		$text=$this->cache[$id];
  	}else{
	  	$this->model->setLang_code($this->code);
	  	$this->model->setCode($id);
	    $this->model->load();
	    $data=$this->model->next();
	  	$text=isset($data['string'])?($data['string']):$id;
	  	$this->cache[$id]=$text;
  	}
  	
  	//htmlspecialchars_decode
  	$text=str_replace("&gt;",">",$text);
  	$text=str_replace("&lt;","<",$text);
  	$text=str_replace("&quot;","\"",$text);
  	$text=str_replace("&#039;","'",$text);
  	$text=str_replace("&amp;","&",$text);
  	
  	return $text;
  }
  
}
?>