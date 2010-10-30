<?php

include_once("root.php");

include_once(ROOT."config.php");

include_once(ROOT."classes/admin/element.php");



class upload_image extends Element{

  

  function edit($i,$j,$display="inline"){

    return "<input type=\"file\" style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" name=\"{$this->target}[]\"/>";

  }

  

 /* 

  function get($value,$i=0){

    $filename="";

    if($_FILES[$this->target]['size'][$i]!=0 && substr($_FILES[$this->target]['name'][$i],-3,3)!='php'){

    	$path=explode(".",$_FILES[$this->target]['name'][$i]);

        $filename=substr(md5(microtime()),0,8).'.'.$path[count($path)-1];

        $this->params['FILEPATH'] = IMG_FILES_TOPVIDEOS;

        $dest=!empty($this->params['FILEPATH'])?$this->params['FILEPATH']:IMG_FILES_TOPVIDEOS;

        

        Debug::write($dest);



        copy($_FILES[$this->target]['tmp_name'][$i],ROOT.$dest."/".$filename);

    }

    if($filename=="")return "{no-change}";

    else return $filename;

  }

 */ 



  function get($value,$i=0){

    $filename="";

    if($_FILES[$this->target]['size'][$i]!=0 && substr($_FILES[$this->target]['name'][$i],-3,3)!='php'){

    	$path=explode(".",$_FILES[$this->target]['name'][$i]);

        $filename=substr(md5(microtime()),0,8).'.'.$path[count($path)-1];

        $this->params['FILEPATH'] = IMG_FILES_ORIG_TOPVIDEOS;

        $dest=!empty($this->params['FILEPATH'])?$this->params['FILEPATH']:IMG_FILES_ORIG_TOPVIDEOS;

        

        Debug::write($dest);

		

        copy($_FILES[$this->target]['tmp_name'][$i],ROOT.$dest."/".$filename);

        //CREACION DEL THUMBNAIL

        

        $orig = $dest;

        $dest = IMG_FILES_TOPVIDEOS;

        

        $this->createThumbnail($orig, $filename, $dest, 378, 251); 

    }

    if($filename=="")return "{no-change}";

    else return $filename;

  }

  

  function display($i,$j){

	if(!empty($this->value) && file_exists(ROOT.IMG_FILES_ORIG_TOPVIDEOS.'/'.$this->value)){

		$image=ROOT.IMG_FILES_ORIG_TOPVIDEOS.'/'.$this->value;

	}elseif(!empty($this->value) && file_exists(ROOT.IMG_FILES_ORIG_TOPVIDEOS.'/'.$this->value)){

		$image=ROOT.IMG_FILES_ORIG_TOPVIDEOS.'/'.$this->value;

	}else{

		$image=ROOT.'images/blankthumb.jpg';

	}

	

  	if(!empty($this->params['LINK'])){

		$href=str_replace("{id}",$this->params['ID'],$this->params['LINK']);

		$href=str_replace("{value}",$this->value,$href);

		return "<a id=\"field{$i}_{$j}\" name=\"$this->target[]\" href=\"$href\"><img src=\"$image\" width=\"60\" height=\"45\"/></a>";

	}else{

		return "<img id=\"field{$i}_{$j}\" src=\"$image\" width=\"60\" height=\"45\"/>";

	}

  }

    

  function createThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight){

	$srcImg = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/$imageDirectory/$imageName");

	//die($_SERVER['DOCUMENT_ROOT']."/$imageDirectory/$imageName");

	$origWidth = imagesx($srcImg);

	$origHeight = imagesy($srcImg);

	

	$ratio = $origWidth / $thumbWidth;

	//$thumbHeight = $origHeight * $ratio;

	

	$thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);



	imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, imagesx($thumbImg), imagesy($thumbImg));

	imagejpeg($thumbImg, $_SERVER['DOCUMENT_ROOT']."/$thumbDirectory/$imageName", 100);

	//die("$thumbImg, $thumbDirectory/$imageName");

  }

}

?>