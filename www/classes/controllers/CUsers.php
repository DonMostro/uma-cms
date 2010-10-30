<?php
include_once("root.php");
include_once(ROOT."classes/controllers/CCommand.php");
include_once(ROOT."classes/views/VUser.php");

class CUsers extends CCommand {
	
	function CUsers($parent){
		parent::CCommand($parent);
	}
	
	function run(){
		if($this->user->username==""){
			$vuser=new VUser();
	  		$vuser->location=$this->form->m;
	  		$this->content=$vuser->login_form();
	  		$this->parent=null;
		}
	}
}
?>