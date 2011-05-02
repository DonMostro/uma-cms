<?php

include_once("root.php");

include_once(ROOT."config.php");

include_once(ROOT."classes/lib/Form.php");

include_once(ROOT."classes/lib/Types.php");

include_once(ROOT."classes/models/MEmailExports.php");

include_once(ROOT."classes/models/MUser.php");

include_once(ROOT."classes/admin/auth.php");



DAO::connect();

$form=new Form();



$auth=new Auth();



$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');



if(!empty($form->e)){

	$exports=new MEmailExports();

	$exports->setId($form->e);

	$exports->load();

	$data=$exports->next();

	$end=$data['tt'];

	

	$exports=new MEmailExports();

	$exports->setTt_last($end);

	$exports->addOrder(new DataOrder('tt'));

	$exports->load();

	$data=$exports->next();

	if(!empty($data)){

		$begin=$data['tt'];

	}else{

		$begin=0;

	}

	

	$users=new MUser();

	$users->setTt_begin($begin);

	$users->setTt_end($end);

	$users->load();

	

	$filename='email_dump_'.gmstrftime('%Y%d%m%H%M%S').'.csv';

	$ff=fopen(ROOT.FILES.'/'.$filename,'w');

	

	while($u=$users->next())fputcsv($ff,array(html_entity_decode($u['email'])));

	

	fclose($ff);

	header('Localización: '.URL.'/'.FILES.'/'.$filename);

}

?>