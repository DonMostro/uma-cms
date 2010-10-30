<?php
include_once("root.php");
include_once(ROOT."classes/admin/element.php");
include_once(ROOT."classes/lib/Types.php");

class sizefield extends Element{
	function display($i,$j){
		$size=new Size($this->value);
		return "<span id=\"field{$i}_{$j}\" name=\"$this->target[]\">".$size->getValue()."</a>";
	}
}
?>