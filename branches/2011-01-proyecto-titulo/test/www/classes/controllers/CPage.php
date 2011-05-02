<?php
ini_set("display_errors", "on");
error_reporting(E_ALL);
include_once("root.php");
include_once(ROOT."classes/controllers/CCommand.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Component.php");
include_once(ROOT."classes/models/MPages.php");
include_once(ROOT."classes/models/MMenu.php");
//include_once(ROOT."classes/models/MPageElements.php");
include_once(ROOT."classes/views/VView.php");

/**
 * P&aacute;ginas customizadas
 *
 */
class CPage extends CCommand {
	
	function CPage(){
		parent::CCommand();
	}
	
	function run(){
		$pagename=$this->form->p;
		//$acl=new ACL($this->user->username,'pages',$pagename);
		//if($acl->canAccess()){		
		if(true){	
			$pagemodel=new MPages();
			$pagemodel->setId($pagename);
			$pagemodel->load();
			$info=$pagemodel->next();
			
			$view=new VView();
			
			if(!empty($info['text'])){
				$view->tplfile=html_entity_decode($info['text']);
				$code=$view->tplfile;
			}else{
				$lang=isset($_COOKIE['lang'])?$_COOKIE['lang'].'/':'';
				$view->tplfile=$lang.$info['id'].'.html';
				if(!file_exists(ROOT.'templates/'.$view->tplfile))$view->tplfile=$info['id'].'.html';
				$code=@file_get_contents(ROOT.'templates/'.$view->tplfile);
			}
			
				//		var_dump($info);
			$matches=array();
			preg_match_all('#\[\:(.+?)\:\]#',$code,$matches);
			if(!empty($matches[1])){
				$files=glob(ROOT.'components/*.xml');
				$files=array_map('basename',$files);
				foreach ($matches[1] as $c){
					if(in_array($c.'.xml',$files)){
						$component=new Component(ROOT.'components/'.$c.'.xml');
						//$component->username=$this->user->username;
						$component->run();
						$view->$c=$component->show();
					}
				}
			}
				
			/*$elements=new MPageElements();
			$elements->setpages_id($pagename);
			$elements->load();
			while($e=$elements->next()){
				$component=new Component(ROOT.'components/'.$e['elements_id'].'.xml');
				$component->username=$this->user->username;
				$component->run();
				$view->$e['elements_id']=$component->show();
			}*/
			
			//$view->username=$this->user->username;
			
			$this->content=$view->show();
			

			
			/*$menu=&new MMenu();
			$menu->setTitle($info['title']);
			$menu->load();
			$subinfo=$menu->next();*/
			
			
			$this->page->title=$info['title'];
		}else{
			$this->content=$this->lang->getText('E_ACCESS_DENIED');
		}
	}
}
?>
