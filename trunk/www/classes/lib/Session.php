<?php
include_once("root.php");
include_once(ROOT."classes/lib/Debug.php");

class Session{
  
  function Session($name){
	session_name($name);
	@session_start();
  }
  
  function getId(){
  	return session_id();
  }
  
  function set($name, $value){
    $_SESSION[$name]=$value;
  }
  
  function remove($name){
    unset($_SESSION[$name]);
  }
  
  function get($name){
    if(isset($_SESSION[$name])){
    	return $_SESSION[$name];
    }else{
    	return null;
    }
  }
  
  function exists($name){
    return isset($_SESSION[$name]);
  }
}
?>