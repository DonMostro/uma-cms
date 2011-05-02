<?php
@session_start();

ini_set("display_errors","on");
error_reporting(E_ALL);


include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/models/MThumbnails.php");
//include_once(ROOT."classes/models/MChannels.php");
include_once(ROOT."classes/admin/auth.php");

DAO::connect();

$form=new Form();

$auth=new Auth();
$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');

if(isset($form->i)){
	echo "<html>\r\n
		<body><img src=\"".URL."/".FILES."/$form->i\"/></body>\r\n
	  </html>";
}
?>