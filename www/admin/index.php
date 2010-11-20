<?php
session_start();
ini_set("display_errors","On");
error_reporting(E_ALL);

include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Template.php");
include_once("xml.php");
include_once(ROOT."classes/admin/auth.php");
include_once(ROOT."classes/admin/table.php");
include_once(ROOT."classes/admin/summary.php");
include_once(ROOT."classes/admin/settings.php");
include_once(ROOT."classes/admin/phpinfo.php");
include_once(ROOT."classes/admin/importvideos.php");
include_once(ROOT."classes/admin/sendnewsletter.php");
include_once(ROOT."classes/admin/contact.php");
include_once(ROOT."classes/models/MSummTotalVideos.php");
include_once(ROOT."classes/models/MSummTotalReports.php");
include_once(ROOT."classes/models/MSummTotalCategories.php");
include_once(ROOT."classes/models/MSummTotalUsers.php");
include_once(ROOT."classes/models/MSummTotalComments.php");
include_once("pages.php");
include_once("include_parts/parts.php");



DAO::connect();
$form=new Form();
$xml=new XML();

$auth=new Auth();
echo "WTF";
print_r($_SESSION);

if(isset($form->logout)){
	$auth->logout();
}else{
	$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');
}


$list="";
foreach ($pages as $title=>$page){
	if(!is_array($page)){
		if(file_exists('images/'.strtolower($title).'.png'))$image='<img style="vertical-align:middle" src="'.'images/'.strtolower($title).'.png'.'" />';
		else $image='';
    	$list.="<a class=\"tab\" href='index.php?p=$page'>$image $title</a>\r\n";
	}else{
		if(file_exists('images/'.strtolower($title).'.png'))$image='<img style="vertical-align:middle" src="'.'images/'.strtolower($title).'.png'.'" />';
		else $image='';
		$list.="<a class=\"tab\" id=\"$title\" href='javascript:void(0)'>$image $title</a>\r\n";
		$list.="<div class=\"menu\" id=\"menu$title\">";
		foreach($page as $subtitle=>$subpage){
			$list.="<a class=\"subtab\" href='index.php?p=$subpage'>$subtitle</a>\r\n";
		}
		$list.="</div>
		<script type=\"text/javascript\">
		document.getElementById('$title').onclick=showmenu;
		document.getElementById('$title').onmouseout=function(){
			menuTimeOut['$title']=setTimeout('hidemenu(\"menu$title\")',100);
		};	
		document.getElementById('menu$title').onmouseout=function(){
			menuTimeOut['$title']=setTimeout('hidemenu(\"menu$title\")',100);
		};		
		document.getElementById('menu$title').onmouseover=function(){
			clearTimeout(menuTimeOut['$title']);
		};	
		</script>
		";
	}
}
       
$content="";

if(isset($form->p)){
	$xml->parse("xml/".$form->p.".xml");
	$view=new $xml->elements[0]['TYPE']($form->p);
	$content=$view->display();
}else{
	$view=new Summary();
	
	$totalvideos=new MSummTotalVideos();
	$view->addModel($totalvideos, "Total videos", "index.php?p=videos");
	
	$reported=new MSummTotalReports();
	$reported->setResolved(0);
	$view->addModel($reported, "Unresolved reports", "index.php?p=reports&resolved=0");
	$view->addAlert("Unresolved reports",0);
	
	
	$approvedvideos=new MSummTotalVideos();
	$approvedvideos->setApproved(0);
	$view->addModel($approvedvideos, "Not approved videos", "index.php?p=videos&approved=0");
	$view->addAlert("Not approved videos",0);
	
	$view->addModel(new MSummTotalCategories(), "Total categories", "index.php?p=categories&parent_id=0");
	$view->addModel(new MSummTotalUsers(), "Total users", "index.php?p=users");
	
	$content=$view->display();
}

if(isset($form->ajax)){
	echo $content;
}else{

	$tpl=new Template("template.html");
	$select = '';
	$select.=(@$_GET['p'] == 'top_carrusel' || @$_GET['p'] == 'top_carrusel_test') ? Parts::SelectCategories() : '';
	$select.=(@$_GET['p'] == 'videos') ? Parts::IncludeAutocomplete() : '';
	$tpl->select=$select;
	$tpl->list=$list;
	$tpl->content=$content;

	echo $tpl->output();
}
?>