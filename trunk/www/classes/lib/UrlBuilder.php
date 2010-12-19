<?php
include_once("root.php");
include_once(ROOT."config.php");

/**
 * Valid URL builder.
 *
 */
class UrlBuilder{

	private $command;
	private $params;
	private $mod_rewrite;
	
	public function UrlBuilder($command, $mod_rewrite=MOD_REWRITE){
		$this->command=$command;
		$this->params=array();
		$this->mod_rewrite=$mod_rewrite;
	}
	
	public function addParam($param, $value){
		$this->params[$param]=urlencode($value);
	}
	
	/**
	 * Builds a standard, mod_rewritten, and optionally html encoded url
	 *
	 * @param string $encode
	 * @return Valid URL
	 */
	public function build($encode=true){
		if($this->mod_rewrite){
			$url="$this->command";
			foreach ($this->params as $key=>$val){
				$url.="/$val";
			}
		}else{
			$url="index.php?m=$this->command";
			foreach ($this->params as $key=>$val){
				if(!is_numeric($key))$url.="&$key=$val";
			}
		}
		if($encode)$url=htmlspecialchars(URL."/".$url);
		else $url=URL."/".$url;
		return $url;
	}
	
	public function __set($name, $value){
		$this->$name=$value;
	}
	
	public function __get($name){
		return $this->$name;
	}
}
?>