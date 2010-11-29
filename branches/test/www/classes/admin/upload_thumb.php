<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/admin/element.php");

class upload_thumb extends Element{
  function edit($i,$j,$display="inline"){
    return "<input type=\"file\" style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" name=\"{$this->target}[]\"/>";
  }
  function get($value,$i=0){

/*	echo '<br/>Files:';
    print_r($_FILES);
    echo '<br/>Post:';
    print_r($_POST); */

    $filename="";
  //  echo $this->target;
    if($_FILES[$this->target]['size'][$i]!=0 && substr($_FILES[$this->target]['name'][$i],-3,3)!='php'){
    	$path=explode(".",$_FILES[$this->target]['name'][$i]);
        $filename=substr(md5(microtime()),0,8).'.'.$path[count($path)-1];
        $dest=!empty($this->params['FILEPATH'])?$this->params['FILEPATH']:THUMBNAILS;
        Debug::write($dest);
     /*echo $_FILES[$this->target]['tmp_name'][$i];
       echo '<br/>'.ROOT.$dest."/".$filename;
       die();*/
        copy($_FILES[$this->target]['tmp_name'][$i],ROOT.$dest."/".$filename);
    }
    //else die('No pasa na con el Upload');
    if($filename=="")return "{no-change}";
    else return $filename;
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