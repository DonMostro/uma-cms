<?php

include_once("root.php");
include_once(ROOT."classes/admin/element.php");

class status extends Element{
  function edit($i, $j, $display="inline"){
  	  $selected=array('','','');
  	  if($this->value=='1'){
  	  	$selected[1] = "selected=\"selected\"";
  	  }elseif($this->value=='-1'){
  	  	$selected[2] = "selected=\"selected\"";
  	  }else{
  	  	$selected[0] = "selected=\"selected\"";
  	  }
	  return "<select style=\"width:50px;display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\">
	  <option value=\"0\" $selected[0]>Pending</option>
	  <option value=\"1\" $selected[1]>Complete</option>
	  <option value=\"-1\" $selected[2]>Cancelled</option>";
  }

  function display($i, $j){
	  switch($this->value){
	  	case 1: $status='Complete'; break;
	  	case -1: $status='Cancelled'; break;
	  	default: $status='Pending';
	  }
	  return "<span id=\"field{$i}_{$j}\" >$status</span>";
  }

  function get($value){
    return $value;
  }
}
?>