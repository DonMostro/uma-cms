<?php
include_once("root.php");
include_once(ROOT."config.php");
final class UserAgent {
	private $user_agent;
	
	function __construct() {
		$this->user_agent=$_SERVER['HTTP_USER_AGENT'];
	}
	/**
	 * busca agente de usuario, puede recibir una lista separada por comas
	 * @param $user_match string a buscar en el agente de usuario
	 * @return boolean
	 */
	
	function getMatch($user_match){
		$arr_user_match=explode(",",$user_match);
		if(is_array($arr_user_match)){
			foreach($arr_user_match as $user_match){
				if(preg_match(strtolower("/$user_match/i"), strtolower($this->user_agent))){
					$return=true;	
				}else{
					$return=false;
				} 
				return $return;
			}
		}else{
			return(preg_match(strtolower("/$user_match/i"), strtolower($this->user_agent)));
		}	 
	}
	
	/**
	 * 
	 */
	function __destruct() {
		
	//TODO - Insert your code here
	}
}

?>