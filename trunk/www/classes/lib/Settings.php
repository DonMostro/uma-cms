<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/models/MSettings.php");

class Settings {
	
  private $settings;
	
  function Settings(){
    $settings=new MSettings();
	$settings->load();
	while($row=$settings->next())$this->settings[$row['id']] = $row['value'];
  }
  
  function &getInstance(){
  	static $me;
  	if(!$me) {
  		$me=array(new Settings());
  	}
  	return $me[0];
  }
  
  function &getSettings(){ 
  	return $this->settings;
  }
  
}
?>