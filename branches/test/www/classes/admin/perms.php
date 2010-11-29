<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class perms extends Element{

  

  function edit($i, $j, $display="inline"){

  	  $selected=array('','','');

  	  $selected[$this->value] = "selected=\"selected\"";

	  return "<select style=\"width:50px;display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\">

	  <option value=\"0\" $selected[0]>No Access</option>

	  <option value=\"1\" $selected[1]>Read</option>

	  <option value=\"2\" $selected[2]>Modify</option>";

  }

  

  function display($i, $j){

	  switch($this->value){

	  	case 1: $value='Read'; break;

	  	case 2: $value='Modify'; break;

	  	default: $value='No Access';

	  }

	  return "<span id=\"field{$i}_{$j}\" name=\"$this->target[]\" />$value</span>";

  }

  

  function get($value){

    return $value;

  }

  

}

?>