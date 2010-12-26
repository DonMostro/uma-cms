<?php
include_once("root.php");
include_once(ROOT."classes/controllers/CCommand.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/views/VCategory.php");
include_once(ROOT."classes/lib/Types.php");

class CLatestRSS extends CCommand {
	
	function CLatestRSS(){
		parent::CCommand();
	}
	
	function show(){
		$this->form->format='xml';
		
		$videosmodel=new MVideos();
		
    	$view=new VCategory($videosmodel);
		$videosmodel->setLimit(20);
	  	$videosmodel->addOrder(new DataOrder('tt'));
		$videosmodel->load();
		
		$view->description=SITENAME.' - '.$this->lang->getText('T_VIDEOS').' - '.$this->lang->getText('T_LATEST');
		
	    $view->tplitemfile='rss_item.xml';
	    $view->tplfile='rss.xml';
/*		if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) 
			header("Content-Type: application/xhtml+xml; charset=utf-8");
		else
			header("Content-Type: text/html; charset=utf-8");*/
	  	return $view->show();
	}
}
?>