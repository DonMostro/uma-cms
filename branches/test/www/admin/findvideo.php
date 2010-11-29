<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/admin/auth.php");

DAO::connect();
$form=new Form();

$auth=new Auth();

$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');

if(!empty($form->target)&&!empty($form->label)){
	$videos=new MVideos();
	$videos->setSearch($form->search);
	$videos->addOrder(new DataOrder('title','ASC'));
	$videos->load();
	echo '<ul>';
	while ($v=$videos->next()){
		echo '<li><a href="javascript:void(0)" onclick="document.getElementById(\''.$form->target.'\').value=\''.$v['id'].'\'; document.getElementById(\''.$form->label.'\').value=\''.str_replace('"','&quot;',$v['title']).'\'; close_context();">'.str_replace('"','&quot;',$v['title'],$v['title']).'</a></li>';
	}
	echo '</ul>';
}
?>