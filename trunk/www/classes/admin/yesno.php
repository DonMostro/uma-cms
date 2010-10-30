<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class yesno extends Element{

  

  function edit($i, $j, $display="inline"){

  	  $selected=array('','');

  	  if($this->value=='1'){

  	  	$selected[1] = "selected=\"selected\"";

  	  }else{

  	  	$selected[0] = "selected=\"selected\"";

  	  }

	  return "<select style=\"width:50px;display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\">

	  <option value=\"1\" $selected[1]>Yes</option>

	  <option value=\"0\" $selected[0]>No</option>";

  }

  

  function display($i, $j){

	  $checked = $this->value==1 ? 'checked="checked"' : "";

	  return "<input type=\"checkbox\" $checked disabled=\"disabled\" id=\"field{$i}_{$j}\" name=\"$this->target[]\" />";

  }

  

  function get($value){

    return $value==1 ? 1 : 0;

  }

  

}

?>