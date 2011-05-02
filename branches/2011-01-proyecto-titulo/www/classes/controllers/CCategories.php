<?php
include_once("root.php");
include_once(ROOT."classes/controllers/CCommand.php");
include_once(ROOT."classes/models/MCategoryList.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/views/VCategoryList.php");
include_once(ROOT."classes/views/VCategory.php");

class CCategories extends CCommand {
	function CCategories(){
		parent::CCommand();
	}
	function run(){

		$catlistmodel=new MCategoryList();
		$cat_list=new VCategoryList($catlistmodel);
		$catlistmodel->setParent_id(0);
		$catlistmodel->load();
		$acl=new ACL($this->user->username,'categories');
		if($acl->canModify()){
			$cat_list->modify='true';
		}

		$this->content=$cat_list->show();
		$this->page->title=$this->lang->getText('T_CATS');
	}
}

?>