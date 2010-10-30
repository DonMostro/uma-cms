<?php
include_once("root.php");
include_once(ROOT."classes/lib/Debug.php");

class Thumbnail{
  function makeThumb($file,$wd,$hg,$dest,$crop=false){
    $path=explode('/',$file);
    $name=$path[count($path)-1];
    list($width, $height, $type, $attr) = getimagesize(ROOT.$file);
    switch($type){
      case 1:
        $img=imagecreatefromgif(ROOT.$file);
        break;
      case 2:
        $img=imagecreatefromjpeg(ROOT.$file);
        break;
      case 3:
        $img=imagecreatefrompng(ROOT.$file);
        break;
    }
    if($img){
        if($width>$wd&&$width>$height){
        	$nhg=$height*($wd/$width);
        	$nwd=$wd;
        }
        elseif($height>$hg){
        	$nwd=$width*($hg/$height);
        	$nhg=$hg;
        }
        else return $file;
        if($crop){
    		$thumb=imagecreatetruecolor($wd,$hg);
    		$ox=$wd/2-$nwd/2; $oy=$hg/2-$nhg/2;
    	}else{
    		$thumb=imagecreatetruecolor($nwd,$nhg);
    		$ox=0; $oy=0;
    	}
        $black = imagecolorallocate($thumb, 0, 0, 0);
        imagefill($thumb, 0, 0, $black);
        imagecopyresampled($thumb, $img, $ox, $oy, 0, 0, $nwd, $nhg, $width, $height);
        imagejpeg($thumb,ROOT.$dest);
        return $dest;
    }else return $file;
  }
}
?>
