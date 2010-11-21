<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);

include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Dispatcher.php");
include_once(ROOT."classes/lib/DAO.php");

$app=new Dispatcher($map, $noncached);

$dao=new DAO();

if(!DAO::connect()){
	echo("<p>&excl;Vaya! estamos con problemas t&eacute;cnicos, por favor vuelve mas tarde</p>");
}else{
	$app->run();
}
?>