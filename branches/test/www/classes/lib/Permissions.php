<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/models/MPermissions.php");

class Permissions {
	
  var $permissions;
	
  function Permissions(){
    $permissions=&new MPermissions();
	$permissions->load();
	while($row=$permissions->next())$this->permissions[$row['resource']] = $row['level'];
  }
  
  function &getInstance(){
  	static $me;
  	if(!$me) {
  		$me=array(new Permissions());
  	}
  	return $me[0];
  }
  
  function &getPermissions(){ 
  	return $this->permissions;
  }
  
}