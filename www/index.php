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
	echo("<h2>&iexcl;Vaya! algo no result&oacute; como lo esperado, por favor vuelve mas tarde</h2>");
	echo("<img src=\"/images/something-wrong.jpg\"/>");
}else{
	$app->run();
}
?>