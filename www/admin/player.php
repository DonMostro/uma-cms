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
	echo "<html>\r\n
			<head>
			<script type=\"text/javascript\">var baseurl=\"http://www.3tv.cl/\";</script>
			<script type=\"text/javascript\" src=\"../functions.js\"></script>
			<script type=\"text/javascript\" src=\"../ajax.js\"></script>
			<base href=\"".URL."/\"/></head>
			<body>\r\n<div id=\"ajax_box\"><div id=\"player_shadow\">".$player->show()."</div></div>
			<p><a href=\"javascript:history.go(-1)\"><b>Go back</b></a></p>			
			</body>\r\n
		  </html>";
}
?>