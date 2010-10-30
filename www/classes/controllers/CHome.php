<?php
include_once("root.php");
include_once(ROOT."classes/views/VHome.php");
include_once(ROOT."classes/views/VCategory.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/models/MFeaturedVideos.php");
include_once(ROOT."classes/controllers/CCommand.php");
include_once(ROOT."classes/controllers/CPage.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/models/MPageElements.php");
include_once(ROOT."classes/models/MPages.php");
include_once(ROOT."classes/lib/Component.php");

class CHome extends CCommand{
  
  function CHome(){
	
    parent::CCommand();
    
    $page->catlist="";
	$page->title="";
  	
  }
  
  function run(){
  	$this->form->m='page';
  	$this->form->p='home';
  	
  	$c=new CPage();
  	$c->run();
  	
	$this->content=$c->content;

	
  }


}
?>
