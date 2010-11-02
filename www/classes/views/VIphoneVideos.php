<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/PageCtrl.php");
include_once(ROOT."classes/lib/UrlBuilder.php");
include_once(ROOT."classes/views/VCollection.php");
include_once(ROOT."classes/views/VVideo.php");
include_once(ROOT."classes/lib/Types.php");

/**
 * [TODO] El nombre de esta clase describe muy mal lo que hace.
 * Permite usar un objeto Vista sin asociarlo a un objeto Template,   
 * Permite usar PHP con HTML lo que en ciertos casos es mas rápido 
 * Ver implementación en/mobiles y /iphone
 */

class VIphoneVideos extends VView{
	private $model;
	public $title;
	public $thumbnail;
	public $video;
	public $recordset;
	
	function VIphoneVideos($model){
		parent::VView();
	    $this->model=$model;
	}
	
	public function show(){
		$lang=Lang::getInstance();
		if($this->model->getSize()>0){
			$info=$this->model->next();
		}
		$this->recordset=(isset($info)) ? $info : '';
		return (is_array($this->recordset) && count($this->recordset) > 0) ? true : false;
	}
}
?>