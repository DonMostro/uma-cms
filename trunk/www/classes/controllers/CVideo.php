<?php
include_once("root.php");
include_once(ROOT."classes/views/VHome.php");
include_once(ROOT."classes/views/VCategory.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/views/VVideo.php");
include_once(ROOT."classes/models/MFeaturedVideos.php");
include_once(ROOT."classes/controllers/CCommand.php");
include_once(ROOT."classes/controllers/CPage.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/models/MPageElements.php");
include_once(ROOT."classes/models/MPages.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/lib/Component.php");

class CVideo extends CCommand{
  
  function CVideo(){
	
    parent::CCommand();
    
    $page->catlist="";
	$page->title="";
  	
  }
  
  function run(){
  	$this->form->m='page';
  	$this->form->p='video';
  	
  	$c=new CPage();
  	if(isset($this->form->v)){	
  		$videomodel=new MVideos();
  		$videomodel->setId($this->form->v);
		$video=new VVideo($videomodel);
		$videomodel->load();
		$video->show();
	  	$c->run();
	  	$c->page->title=$video->title;
  	}
	$this->content=$c->content;
  }
}
?>
