<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/views/VView.php");

class VHome extends VView {
	
  var $featured;
  var $model;
  var $username;
  var $subscriptions;
  var $tplfile;
  
  function VHome($model){
  	parent::VView();
	$this->model=$model;
	$this->tplfile='home.html';
  }

  function show(){
  		//error_reporting(E_ALL);
  		$VPage = new VPage();
  		$VPage->SetAllRequestItems();
  		include(ROOT."ads/googleChannels.php");
  		//FIN ETIQUETAS
 
  
    $tpl=&new Template(ROOT."html/$this->tplfile");
    $tpl->featured=$this->featured;
    $tpl->username=$this->username;
   /* $tpl->pagination=PageCtrl::getCtrl($this->model->countAll(),$this->model->getStart(),$this->model->getLimit(),URL."/index.php?");*/
   	$tpl->pagination = "";
  // 	echo "<script>alert('$tpl->pagination')</script>";
  //  $tpl->subscriptions=$this->subscriptions?"true":"false";
    
    $this->_setAds($tpl);
    
   // return $tpl->output();
    
  } 

}

?>