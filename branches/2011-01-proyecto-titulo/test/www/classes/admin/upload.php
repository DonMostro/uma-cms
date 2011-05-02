<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/admin/element.php");

ini_set("post_max_size", "256M");
ini_set("upload_max_filesize", "256M");

class upload extends Element{
  function edit($i,$j,$display="inline"){
    return "<input type=\"file\" style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" name=\"{$this->target}[]\"/>";
  }
  function get($value,$i=0){

    $filename="";
	print_r($_FILES);
 /*   echo "^^" . $this->target . "^^";*/
    if($_FILES[$this->target]['size'][$i]!=0 && substr($_FILES[$this->target]['name'][$i],-3,3)!='php'){
//		echo "Estoy AC�";
    	$path=explode(".",$_FILES[$this->target]['name'][$i]);
        $filename=substr(md5(microtime()),0,8).'.'.$path[count($path)-1];
        $dest=!empty($this->params['FILEPATH'])?$this->params['FILEPATH']:THUMBNAILS;
        Debug::write($dest);
     /*  echo $_FILES[$this->target]['tmp_name'][$i];
       echo '<br/>'.ROOT.$dest."/".$filename;
       die();*/
        copy($_FILES[$this->target]['tmp_name'][$i],ROOT.$dest."/".$filename);
    }
    //else die('No pasa na con el Upload');
    if($filename=="")return "{no-change}";
    else return $filename;
  }
}
?>