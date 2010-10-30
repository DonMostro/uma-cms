<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");



class finduser extends Element{

  

  public function edit($i,$j,$display="inline"){

  	$e=parent::edit($i,$j,$display);

  	return $e." <input  style=\"display:$display\" type=\"button\" onclick=\"context('finduser.php?target=edit{$i}_{$j}&amp;search='+escape(document.getElementById('edit{$i}_{$j}').value))\" value=\"Find\"/>";

  }

}

?>