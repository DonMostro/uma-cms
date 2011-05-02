<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class checkbox extends Element{

  

  function edit($i, $j, $display="inline"){

      $checked = $this->value==1 ? "checked=\"checked\"" : "";

	  return "<input type=\"checkbox\" style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" value=\"1\" $checked />";

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