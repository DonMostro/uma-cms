<?php

include_once("root.php");



class PHPInfo{

  

  public $page;

  

  function PHPInfo($page){

    $this->page=$page;

  }

  

  function display(){

    $out = "<h2>Configuración del sistema</h2>\r\n";

	

    ob_start();

    phpinfo(INFO_GENERAL);

	$phpinfo=preg_replace('#<!DOCTYPE.+?<body>#is','',ob_get_clean());

	$out.=str_replace('</body></html>','',$phpinfo);

	

    ob_start();

    phpinfo(INFO_CONFIGURATION);

	$phpinfo=preg_replace('#<!DOCTYPE.+?<body>#is','',ob_get_clean());

	$out.=str_replace('</body></html>','',$phpinfo);

	

	return $out;

  }

  

}





?>