<?php
class Auth{
	function __construct(){
		session_name("vcms_admin");
		session_start();
	}

	public function authenticate($username, $password){
	/*
		if($username==ADMIN_USERNAME && $password==ADMIN_PASSWORD){
			$_SESSION['username']=ADMIN_USERNAME;
		}
	*/
		global $arrAdmin;
		
		if(!empty($username) && !empty($password)){
			for($i=0;$i<=count($arrAdmin['user']);$i++){
				if($username==$arrAdmin['user'][$i] && $password==$arrAdmin['password'][$i]){
					$_SESSION['username']=ADMIN_USERNAME;
				}
			}
		}	
	
		if(!isset($_SESSION['username'])||$_SESSION['username']!=ADMIN_USERNAME){
			include 'login.html';
			exit();
		}elseif(!empty($password)){
			header("Location: ".URL."/admin/index.php");
		}
	}

	public function logout(){
		session_destroy();
		header("Location: ".URL."/admin/index.php");
	}
}

?>