<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class password extends Element{

	

  function edit($i,$j,$display="inline"){

  	return "<input type=\"password\" style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" value=\"{$this->value}\" />";

  }

  

}

?>