<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class textarea extends Element{

  function display($i,$j){

	return "<div id=\"field{$i}_{$j}\" style=\"height:100px;overflow:scroll\" name=\"$this->target[]\">{$this->value}</div>";

  }

  function edit($i, $j, $display="inline"){

  	$width=isset($this->params['WIDTH'])?$this->params['WIDTH']:200;

  	$height=isset($this->params['HEIGHT'])?$this->params['HEIGHT']:100;

    return "<textarea style=\"display:$display;width:{$width}px;height:{$height}px\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\">{$this->value}</textarea>";

  }

  

}

?>