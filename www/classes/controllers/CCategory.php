<?php
include_once("root.php");
include_once(ROOT."classes/views/VHome.php");
include_once(ROOT."classes/views/VCategory.php");
include_once(ROOT."classes/views/VCategoryList.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/models/MFeaturedVideos.php");
include_once(ROOT."classes/commands/CCommand.php");
include_once(ROOT."classes/commands/CPage.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/models/MPageElements.php");
include_once(ROOT."classes/models/MPages.php");
include_once(ROOT."classes/lib/Component.php");

class CCategory extends CCommand {
  function CCategory(){
    parent::CCommand();
    
    $page->catlist="";
	$page->title="";
  	
  }
  
  function run(){
  	if(!isset($this->form->c) || !ctype_digit($this->form->c))header("location:index.php");
	if(isset($this->form->sort)){
  		$videosmodel=new MVideos();
		if(!empty($this->form->exclude))$videosmodel->setExclude($this->form->exclude);
    	if(isset($this->form->sort))$videosmodel->addOrder(new DataOrder($this->form->sort));
		if(isset($this->form->start))$videosmodel->setStart($this->form->start);
		if(isset($this->form->c))$videosmodel->setParent_Id($this->form->c);
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

		$cat=new VCategory($videosmodel);



	    $time=!empty($this->form->time)?$this->form->time:'';
	    $sort=!empty($this->form->sort)?$this->form->sort:'tt';
		$cat->surl="index.php?m=category&amp;sort=$sort&amp;c=".$this->form->c;
	  	$cat->url="index.php?m=category&amp;time=$time&amp;c=".$this->form->c;

		//tags for quick list

	  	$cat->time=$time;
	  	$cat->title='';
	  	if($this->form->sort=="hits") $cat->categories_title="Lo M&aacute;s Reciente";
	  	elseif($this->form->sort=="tt") $cat->categories_title="Lo &Uacute;ltimo";
		$cat->cutter="5";
	  	$this->content=$cat->show();
	  	$this->page->title="";
  	
	}else{
	  	$this->form->m='page';
	  	$this->form->p='category';

	  	$categoriesmodel=new MCategoryList();
	  	$categoriesmodel->setId($this->form->c);
  		$categoriesmodel->load();
  		$cat=new VIphoneVideos($categoriesmodel);
  		$cat->show();
	
	  	$c=new CPage();
	  	$c->run();
	  	$c->page->title=$cat->recordset["title"];
	  	
		$this->content=$c->content;
	}	
  }
}
?>