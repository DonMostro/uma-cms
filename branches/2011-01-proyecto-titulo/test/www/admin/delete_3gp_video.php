<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/admin/auth.php");

DAO::connect();
$form=new Form();

$auth=new Auth();

$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');
if(isset($form->v)){
	$s=Settings::getInstance();
	$settings=$s->getSettings();

	$video=new MVideos();
	$video->setId($form->v);
	$video->load();
    @unlink(ROOT.SMALL_VIDEOS.'/'.$data['filename_3gp']);
	$video->setFileName3GP('');			
	$video->update();
	echo 'Video borrado.';
}
?>