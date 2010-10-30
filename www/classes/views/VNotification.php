<?php
include_once("root.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/views/VView.php");

class VNotification extends VView{
  
  var $username;
  var $tplfile;
 
  function VNotification(){
  	parent::VView();
  	$this->tplfile='notification.html';
  }

  function show(){
 	$tpl=new Template(ROOT."templates/$this->tplfile");
  	
  	$tpl->username=$this->username;
  	
  	$tpl->url=URL;
  	$tpl->sitename=SITENAME;
  	
  	$this->_setAds($tpl);
  	
  	return $tpl->output();
  }
  
  function subject(){
  	$lang=Lang::getInstance();
  	$tpl=new Template($lang->getText('M_NOTIFICATION'));
  	
  	$tpl->username=$this->username;
  	
  	$tpl->url=URL;
  	$tpl->sitename=SITENAME;
  	
  	return $tpl->output();
  }

}