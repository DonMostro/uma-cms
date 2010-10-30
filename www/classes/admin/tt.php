<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class tt extends Element{

  

  function edit($i, $j, $display="inline"){

	  $this->value = $this->value=="" ? time() : $this->value;

	  $v=strftime("%Y-%m-%d %H:%M:%S",$this->value);

	  return "<input type=\"text\" style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" value=\"{$v}\" />";

  }

  

  function display($i, $j){

  	$v=strftime("%Y-%m-%d %H:%M:%S",$this->value);



	return "<span id=\"field{$i}_{$j}\" name=\"$this->target[]\">$v</span>";

  }

  

  function get($value,$i=0){

    return strtotime($value);

  }

  

}

?>