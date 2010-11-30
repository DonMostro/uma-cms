<?php
include_once("root.php");
include_once(ROOT."classes/commands/CCommand.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/models/MCategoryList.php");
include_once(ROOT."classes/models/MTags.php");
include_once(ROOT."classes/views/VCategory.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/views/VCategoryList.php");
include_once(ROOT."classes/views/VTagCloud.php");


class CSearch extends CCommand {
	
	function CSearch(){
		parent::CCommand();
	}
	
	function run(){
		$acl=new ACL($this->user->username,'videos');
		if($acl->canAccess()){
		  	$videosmodel=new MVideos();
		    $cat=new VCategory($videosmodel);
		
		    if(!empty($this->form->exclude))$videosmodel->setExclude($this->form->exclude);
			if(isset($this->form->sort))$videosmodel->addOrder(new DataOrder($this->form->sort));
			if(isset($this->form->start))$videosmodel->setStart($this->form->start);
			if(isset($this->form->search))$videosmodel->setSearch($this->form->search);
			if(!empty($this->form->time)){
				switch($this->form->time){
					case 'today': $videosmodel->setTt(time()-86400); break;
					case 'week': $videosmodel->setTt(time()-604800); break;
					case 'month': $videosmodel->setTt(time()-2592000); break;
					default: ;
				}
			}
			$videosmodel->setApproved(1);
			$videosmodel->setLimit(20);
			$videosmodel->load();
			
			//$cat->title=$this->lang->getText('T_VIDEOS');
			
			$time=!empty($this->form->time)?$this->form->time:'';
			$sort=!empty($this->form->sort)?$this->form->sort:'tt';
			$cat->surl="index.php?m=search&amp;sort=$sort&amp;search=".urlencode(stripslashes($videosmodel->getSearch()));
			$cat->url="index.php?m=search&amp;time=$time&amp;search=".urlencode(stripslashes($videosmodel->getSearch()));
			$cat->cutter="5";
			$cat->categories_title='Resultados de b&uacute;squeda de &quot;'.strip_tags($videosmodel->getSearch()).'&quot;';
			

			$this->content=$cat->show();
			$this->page->title='"'.strip_tags($videosmodel->getSearch()).'"';

		}else{
			$this->content=$this->lang->getText('E_ACCESS_DENIED');
		}
	}
}
?>