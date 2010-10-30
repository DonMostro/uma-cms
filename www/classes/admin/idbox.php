<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class idbox extends Element{

  

  function edit($i, $j, $display="inline"){

    return "<input type=\"hidden\" name=\"{$this->target}[$i]\" value=\"{$this->value}\"/>$this->value";

  }

  

  function display($i, $j){

    return "<input id=\"field{$i}_{$j}\" type=\"checkbox\" name=\"{$this->target}[]\" value=\"{$this->value}\"/>";

  }

  

}

?>