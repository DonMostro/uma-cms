<?php
include_once("root.php");
include_once(ROOT."classes/controllers/CCommand.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/views/VVideo.php");

class CFilename extends CCommand {

	function CFilename(){
		parent::CCommand();
	}

	function show(){
		$s=Settings::getInstance();
		$settings=$s->getSettings();
		$videmodel=new MVideos();
		$vidview=new VVideo($videmodel);
		if(isset($this->form->id))$videmodel->setId($this->form->id);
		$videmodel->setApproved(1);
		$type = (isset($this->form->type)) ? $this->form->type: $settings['default_type'];
		
		$videmodel->loadFilename($type);
				
		if(isset($this->form->f)){
			switch($this->form->f){
				case "redir":
					$info=$videmodel->next();
					if(empty($info['filename']))$info['filename']=$info['orig_file'];
					if(empty($info['server'])||$info['server']=='localhost'){
			    		$url=URL.'/'.FILES.'/'.$info['filename'];
			    	}else{
			    		$url=rtrim($info['url'], '/').'/'.$info['filename'];
			    	}
					header("Location: ".$url);
					break;
				default: $this->form->format='xml'; $vidview->tplfile="video.xml";
			}
		}else{
			$this->form->format='xml';
			$vidview->tplfile="video.xml";
		}
		$videmodel->view(); /*	2010-04-13 movido de CVideo() */
		return $vidview->show();
	}
}
?>