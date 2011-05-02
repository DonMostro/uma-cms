<?php
include_once("root.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Debug.php");

class PageCtrl{
  function getCtrl($count,$start,$limit,$url,$ajax=false,$ajax_target="ajax_box",$prefix='',$is_video_comment=false){
  	$isNext=false;
  	$isPrev=false;
    if($start-$limit>=0){
        $prev=$start-$limit;
        $isPrev=true;
    }
   
    $pages=$limit==0?0:ceil($count/$limit);
    $page_numbers="";
    if($pages>10&&$limit>0){
	  $pstart=floor($start/$limit/10)*10;
	  if($pages-$pstart<11)$pcount=$pstart+($pages-$pstart);
	  else $pcount=$pstart+11;
	  if($pstart>0)$pstart--;
	}
    else{
	  $pcount=$pages;
	  $pstart=0;
	}
	if($pstart>0){
		if ($is_video_comment) {
       		$video_id = $_GET['v'];
    		$strCertifica= "marcaCambioConId('3tv/video/$video_id/comentarios/pagina1', 106340);";
    	}else $strCertifica = "";
			
		
		if($ajax)$page_numbers.="<a class=\"pagenum\" href=\"javascript:void(0)\" onclick=\"get_url_contents('$url&start=0','$ajax_target');$strCertifica\">1</a> ";
    	else $page_numbers.="<a class=\"pagenum\" href=\"$url&{$prefix}start=0\">1</a> ";
    	$page_numbers.="<span class=\"pagenum\">...</span> ";
	}
    for($i=$pstart; $i<$pcount; $i++){
      if($i*$limit==$start){
      	$num=$i+1;
      	$page_numbers.="<span class=\"pagenumh\">$num</span> ";
      }else{
        $pos=$i*$limit;
        $num=$i+1;
        
        if ($is_video_comment) {
       		$video_id = $_GET['v'];
    		$strCertifica= "marcaCambioConId('3tv/video/$video_id/comentarios/pagina$num', 106340);";
    	}else $strCertifica = "";
        
        if($ajax)$page_numbers.="<a class=\"pagenum\" href=\"javascript:void(0)\" onclick=\"get_url_contents('$url&start=$pos','$ajax_target');$strCertifica\">$num</a> ";
        else $page_numbers.="<a class=\"pagenum\" href=\"$url&{$prefix}start=$pos\">$num</a> ";
      }
    }
    if($pages>$pcount){
    	if ($is_video_comment) {
       		$video_id = $_GET['v'];
    		$strCertifica= "marcaCambioConId('3tv/video/$video_id/comentarios/pagina$num', 106340);";
    	}else $strCertifica = "";
    	$page_numbers.="<span class=\"pagenum\">...</span> ";
    	if($ajax)$page_numbers.="<a class=\"pagenum\" href=\"javascript:void(0)\" onclick=\"get_url_contents('$url&start=".($pages-1)*$limit."','$ajax_target');$strCertifica\">$pages</a> ";
    	else $page_numbers.="<a class=\"pagenum\" href=\"$url&{$prefix}start=".($pages-1)*$limit."\">$pages</a> ";
    }
    if(($start+$limit)<$count){
        $next=$start+$limit;
        $isNext=true;
    }
    $prev=isset($prev)?$prev:"";
    $next=isset($next)?$next:"";
    $output="";
     
    if ($is_video_comment) {
  		$video_id = $_GET['v'];
  		$prevLimit = $prev/$limit + 1;
  		$nextLimit = $next/$limit + 1;
  		
   		$strCertificaPrev= "marcaCambioConId('3tv/video/$video_id/comentarios/pagina$prevLimit', 106340);";
   		$strCertificaNext= "marcaCambioConId('3tv/video/$video_id/comentarios/pagina$nextLimit', 106340);";
   	}else{
   		$strCertificaPrev = "";
   		$strCertificaNext = "";
   	}
    
    
    if($isPrev&&$ajax)$output.="<a class=\"pagenum\" href=\"javascript:void(0)\" onclick=\" get_url_contents('$url&start=$prev','$ajax_target');$strCertificaPrev\">&laquo;</a> ";
    elseif($isPrev)$output.="<a class=\"pagenum\" href=\"$url&{$prefix}start=$prev\">&laquo;</a> ";
    $output.=$page_numbers;
    if($isNext&&$ajax)$output.="<a class=\"pagenum\" href=\"javascript:void(0)\" onclick=\" get_url_contents('$url&start=$next','$ajax_target');$strCertificaNext\">&raquo;</a>";
    elseif($isNext)$output.="<a class=\"pagenum\" href=\"$url&{$prefix}start=$next\">&raquo;</a>";
    return $output;
  }
}
?>