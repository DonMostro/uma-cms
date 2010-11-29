<?php

include_once("root.php");

include_once(ROOT."config.php");

include_once(ROOT."classes/lib/Form.php");

include_once(ROOT."classes/lib/Types.php");

include_once(ROOT."classes/models/MUser.php");

include_once(ROOT."classes/admin/auth.php");



DAO::connect();

$form=new Form();



$auth=new Auth();



$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');



if(!empty($form->target)){

	$users=new MUser();

	$users->setSearch($form->search);

	$users->addOrder(new DataOrder('username','ASC'));

	$users->load();

	echo '<ul>';

	while ($u=$users->next()){

		echo '<li><a href="javascript:void(0)" onclick="document.getElementById(\''.$form->target.'\').value=\''.$u['username'].'\'; close_context();">'.$u['username'].'</a></li>';

	}

	echo '</ul>';

}

?>