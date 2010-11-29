<?php
include_once("root.php");
include_once(ROOT."classes/admin/element.php");

class findvideo extends Element{
  public function display($i,$j){
  	$dao=new DAO();
    $id=!empty($this->params['TABLEPK'])?$this->params['TABLEPK']:'id';
    $dao->query("SELECT `{$this->params['FIELD']}`,`$id` FROM {$this->params['TABLE']} WHERE `$id`='".mysql_real_escape_string($this->value)."'");
    $title=$dao->get(0,$this->params['FIELD']);
	return "<span id=\"field{$i}_{$j}\" name=\"$this->target[]\">{$title}</span>";
  }
  public function edit($i,$j,$display="inline"){
  	$dao=new DAO();
    $id=!empty($this->params['TABLEPK'])?$this->params['TABLEPK']:'id';
    $dao->query("SELECT `{$this->params['FIELD']}`,`$id` FROM {$this->params['TABLE']} WHERE `$id`='".mysql_real_escape_string($this->value)."'");
    $title=$dao->get(0,$this->params['FIELD']);

  	$e="<input type=\"hidden\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" value=\"{$this->value}\" />";

  	$e.="<input type=\"text\" style=\"display:$display\" id=\"label{$i}_{$j}\" value=\"{$title}\" />";

  	return $e." <input  style=\"display:$display\" type=\"button\" onclick=\"context('findvideo.php?label=label{$i}_{$j}&amp;target=edit{$i}_{$j}&amp;search='+escape(document.getElementById('label{$i}_{$j}').value))\" value=\"Find\"/>";
  }
}
?>