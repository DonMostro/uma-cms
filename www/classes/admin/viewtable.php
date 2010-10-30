<?php

include_once("root.php");

include_once(ROOT."classes/lib/Form.php");

include_once(ROOT."classes/admin/controller.php");

include_once(ROOT."classes/admin/elements_inc.php");



class ViewTable extends Controller{

  

  function display(){

  	$form=new Form();

    $out="<table cellspacing=\"1\">\r\n<tr>";

    $count=count($this->layout);

    $start=isset($form->start)?$form->start:"";

    $search=isset($form->search)?urlencode($form->search):"";

    $text=isset($form->text)?urlencode($form->text):"";

    for($i=1; $i<$count; $i++){

	  if($this->layout[$i]['VISIBLE']&&$this->layout[$i]['TARGET']!=""){

	  	if(isset($form->dir)&&$form->dir=="ASC")$dir="DESC";

	  	else $dir="ASC";

	  	$image="";

	  	if(isset($form->sort)&&$form->sort==$this->layout[$i]['TARGET']){

	  		if(isset($form->dir)&&$form->dir=="ASC")$image="<img src=\"images/arrow_up.png\" />";

	  		else $image="<img src=\"images/arrow_down.png\" />";

	  	}

	  	$out.="<th class=\"theader\"><a class=\"white\" href=\"javascript:void(0)\" onclick=\"get_url_contents('index.php?p=$this->page&start=$start&search=$search&text=$text&sort=".urlencode($this->layout[$i]['TARGET'])."&dir=$dir".$this->getRequested_params()."&ajax=1','ajax_box');sort='".addslashes($this->layout[$i]['TARGET'])."';sortdir='$dir'\">$image {$this->layout[$i]['NAME']}</a></th>";

	  }elseif($this->layout[$i]['VISIBLE']){

	  	$out.="<th class=\"theader\">{$this->layout[$i]['NAME']}</th>";

	  }

    }

	$out.="<th class=\"theader\">&nbsp;</th></tr>\r\n";

	if(isset($this->layout[1]['VALUE'])){

		$vcount=count($this->layout[1]['VALUE']);

	}else $vcount=0;

	for($i=0; $i<$vcount; $i++){

	  $id=0;

	  $out.="<tr class=\"trow\">";

	  for($j=1; $j<$count; $j++){

	    $node=$this->layout[$j];

	    $params=array();

	    foreach($node as $k=>$v)if($k!='VALUE')$params[$k]=$v;

	    if($node['TYPE']=='idbox')$id=$node['VALUE'][$i];

	    $params['ID']=$id;

	    $element=new $node['TYPE']($node['VISIBLE'],$node['EDIT'],$node['NAME'],$node['TARGET'],$node['VALUE'][$i],$params);

	    if($node['VISIBLE'])$out.="<td>".$element->display($i,$j)."</td>\r\n";

	  }

	  $out.="<td>";

	  if(isset($this->layout[0]['EDIT']) && $this->layout[0]['EDIT']=="true"){

	  	$out.="<a id=\"c_edit{$i}\" href=\"javascript:void(0)\" onclick=\"edit($i,$count)\" title=\"Edit\"><img src=\"images/pencil.png\" /></a><img id=\"loading{$i}\" src=\"images/loading.gif\" style=\"display:none\" /><a style=\"display:none\" id=\"c_update{$i}\" href=\"javascript:void(0)\" onclick=\"update($i,$count)\" title=\"Save\"><img src=\"images/accept.png\" /></a> <a style=\"display:none\" id=\"c_cancel{$i}\" href=\"javascript:void(0)\" onclick=\"cancel($i,$count)\" title=\"Cancel\"><img src=\"images/cancel.png\" /></a>";

	  }

	  if(isset($this->layout[0]['DELETE']) && $this->layout[0]['DELETE']=="true"){

	  	$out.=" <a href=\"javascript:void(0)\" onclick=\"if(confirm('Are you sure?')==true){get_url_contents('".$_SERVER['REQUEST_URI']."&a=delete&{$this->layout[1]['TARGET']}[]=$id&ajax=1','ajax_box')}\" title=\"Delete\"><img src=\"images/cross.png\" /></a></td>";

	  }

	  $out.="</tr>\r\n";

	}

	$out.="</table>";

	return $out;

  }

  

}

?>