<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/models/MModel.php");


class MEmoticons extends MModel {
	
  private $emoticons;
  
  function __construct(){
	$this->emoticons=array();
  }
  
  public function load(){
  	if(file_exists(ROOT.'xml/emoticons.xml')){
		$dom=simplexml_load_file(ROOT.'xml/emoticons.xml');
		$e=array();
		foreach ($dom->emoticon as $emoticon){
			$e['title']=(string)$emoticon->title;
			$e['filename']=(string)$emoticon->filename;	
			$e['shortcuts']=array();
			foreach ($emoticon->shortcuts->children() as $alias) {
				$aliastext=(string)$alias;
				if(!empty($aliastext))$e['shortcuts'][]=trim($aliastext);			
			}
			$this->emoticons[]=$e;
		}
	}
	reset($this->emoticons);
  }
  
  public function next(){
  	$e=each($this->emoticons);
  	if($e){
  		return $e[1];
  	}else{
  		return false;
  	}
  }
  
  public function reset(){
  	reset($this->emoticons);
  }
  
  public function getSize(){
  	return count($this->emoticons);
  }
  
}
?>