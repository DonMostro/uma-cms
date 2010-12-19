<?php
@session_start();
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/models/MPlayers.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/views/VPlayer.php");
include_once(ROOT."classes/admin/auth.php");

DAO::connect();
$form=new Form();

$auth=new Auth();

$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');

if(isset($form->v)){
	$video=new MVideos();
	$video->setId($form->v);
	$video->load();
	$vdata=$video->next();
	$mplayer=new MPlayers();
	$mplayer->setType("Backoffice");
	$mplayer->setVideo_Id($form->v);
	$mplayer->load();
	
	$player=new VPlayer($mplayer);
	$player->id=$form->v;
	$player->filename=!empty($vdata['filename'])?$vdata['filename']:'';
	echo $player->show();
}
?>