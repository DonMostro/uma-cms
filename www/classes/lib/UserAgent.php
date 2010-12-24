<?php
include_once("root.php");
include_once(ROOT."config.php");
final class UserAgent {
	private $user_agent;
	
	function __construct() {
		$this->user_agent=$_SERVER['HTTP_USER_AGENT'];
	}
	
	function getMatch($user_match){
		return(preg_match(strtolower($user_match), strtolower($this->user_agent))); 
	}
	
	/**
	 * 
	 */
	function __destruct() {
		
	//TODO - Insert your code here
	}
}

?>