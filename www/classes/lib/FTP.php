<?php

class FTP{
	
	var $con;
	var $status;
	
	function FTP($server, $username, $password){
		$this->con=ftp_connect($server) or Debug::write('[FTP error]: No se puede conectar a '.$server);
		$this->status=ftp_login($this->con, $username, $password) or Debug::write('[FTP error]: No se puede acceder a '.$server.' as '.$username);
	}
	
	function upload($file, $dest, $mode=FTP_BINARY){
		if($this->con && $this->status){
			return ftp_put($this->con, $dest, $file, $mode);
		} else {
			return false;
		}
	}
	
	function chdir($path){
		if($this->con && $this->status && !empty($path)){
			ftp_chdir($this->con, $path);
		}
	}
	
	function disconnect(){
		ftp_close($this->con);
	}
}
?>