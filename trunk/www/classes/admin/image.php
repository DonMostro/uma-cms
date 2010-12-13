<?php

include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/admin/element.php");

class image extends Element{
  function display($i,$j){
	if(!empty($this->value) && file_exists(ROOT.THUMBNAILS.'/'.$this->value)){
		$image=ROOT.THUMBNAILS.'/'.$this->value;
	}elseif(!empty($this->value) && file_exists(ROOT.FILES.'/'.$this->value)){
		$image=ROOT.FILES.'/'.$this->value;
	}else{
		$image=ROOT.'images/blankthumb.jpg';
	}


  	if(!empty($this->params['LINK'])){
		$href=str_replace("{id}",$this->params['ID'],$this->params['LINK']);
		$href=str_replace("{value}",$this->value,$href);
		return "<a id=\"field{$i}_{$j}\" name=\"$this->target[]\" href=\"$href\"><img src=\"$image\"/></a>";
	}else{
		return "<img id=\"field{$i}_{$j}\" src=\"$image\"/>";
	}
  }
}
?>