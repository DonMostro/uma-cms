<?php

include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MMenu.php");
include_once(ROOT."classes/models/MMessages.php");
include_once(ROOT."classes/models/MLanguages.php");
include_once(ROOT."classes/views/VView.php");

include_once(ROOT."classes/views/VIphoneVideos.php");

/**

 * Vista maestra, procesa todos los templates

 *

 */

class VPage extends VView {
  public $title;
  public $content;
  public $keywords;
  public $siteurl;
  public $username;
  public $description;
  public $req_c_parent;
  public $req_c;
  public $req_v;
  public $version = '20100413';
  
  function __construct(){
	parent::__construct();
	$this->keywords='';
  }

  public function getInstance(){
  	static $me;
  	if(!$me) {
  		$me=array(new VPage());
  	}
  	return $me[0];
  }

  
  public function SetAllRequestItems(){
  	DAO::connect();  	
  	//subcanal
  	$this->req_c = isset($_REQUEST['c']) && ctype_digit($_REQUEST['c']) && !empty($_REQUEST['c']) ? $_REQUEST['c'] : 0;
  	//video
  	$this->req_v = isset($_REQUEST['v']) && ctype_digit($_REQUEST['v']) && !empty($_REQUEST['v']) ? $_REQUEST['v'] : 0;
  	
  	
  	//En el XML, ?v ahora es ?id, why?: quien sabe por que ...
  	if($this->req_v == 0 && @$_REQUEST['m'] == 'filename'){
  		$this->req_v = ctype_digit($_REQUEST['id']) && !empty($_REQUEST['id']) ? $_REQUEST['id'] : 0;	  		
  	}
  	
  	//canal
  	$this->req_c_parent = 0;

  	if($this->req_c == 0 && $this->req_v != 0){
  		$strSQL = " SELECT categories_id FROM videos WHERE id = $this->req_v LIMIT 0, 1";
		$qry = mysql_query($strSQL);
		if($row = mysql_fetch_array($qry)){
	  		//canal
	  		$this->req_c = $row['categories_id'];
	  	}
  	}
  	
  	if($this->req_c != 0){
	  	$strSQL = " SELECT parent_id FROM categories WHERE id = $this->req_c LIMIT 0, 1";
		$qry = mysql_query($strSQL);
		if($row = mysql_fetch_array($qry)){
	  		//canal padre
	  		$this->req_c_parent = $row['parent_id'];
	  	}
  	}

  	 	
  	if($this->req_c_parent == 0 && $this->req_v != 0){
		$strSQL = "SELECT categories_id, parent_id 	FROM videos vid LEFT JOIN categories cat ON vid.categories_id = cat.id 
	   	WHERE vid.id = $this->req_v  ";
    	$qry = mysql_query($strSQL);
    	if(mysql_num_rows($qry) > 0){
			$row = mysql_fetch_array($qry);
			$this->req_c_parent = ($row['parent_id'] != 0) ? $row['parent_id'] : $row['categories_id'];
    	}
  	 }	
  }
  
  public function show(){
  	DAO::connect();  	
  	$lang=Lang::getInstance();

  	$tpl=new Template(ROOT."templates/index.html");
	$tpl->version=$this->version;

  	$path=@parse_url(URL);
  	$request_uri=htmlspecialchars(ltrim(str_replace(@$path['path'],'',$_SERVER['REQUEST_URI'])));
  	//subcanal
  	$request_c = isset($_REQUEST['c']) && ctype_digit($_REQUEST['c']) && !empty($_REQUEST['c']) ? $_REQUEST['c'] : 0;
  	//video
  	$request_v = isset($_REQUEST['v']) && ctype_digit($_REQUEST['v']) && !empty($_REQUEST['v']) ? $_REQUEST['v'] : 0;
  	//canal
  	$request_c_parent = 0;

  	$strSQL = " SELECT parent_id FROM categories WHERE id = $request_c ";
	$qry = mysql_query($strSQL);

  	if($row = mysql_fetch_array($qry)){
  		//canal padre
  		$request_c_parent = $row['parent_id'];
  	}	
  	if($request_uri=='/')$request_uri='';

  	//show language selector
  	$languages=new MLanguages();
  	$languages->setApproved(1);
  	$languages->load();

  	$tpl->languages="";
  	while($row=$languages->next()){
  		$tpl2=new Template(ROOT."templates/lang_item.html");
  		$tpl2->url=URL;
  		$tpl2->code=$row['code'];
  		$tpl->languages.=$tpl2->output();
  	}

  	//get the number of new messages
  	if($this->username){
  		$msg=new MMessages();
  		$msg->setTo($this->username);
  		$msg->setNew(1);
  		$msg->setRemoved(0);
  		$inbox=$msg->countAll();

  	}else{
  		$inbox=0;
  	}

  	//create menu onject

  	$menu=new MMenu();
  	$menu->addOrder(new DataOrder('menu_order','ASC'));;
  	$menu->setHeader(1);
  	$menu->setApproved(1);

	//create sub menu (if submenu item is selected)
	$menu->setUrl($request_uri);
	$menu->load();
	$tpl->submenu="";
	$page=0;

	while($item=$menu->next()){
	    $tpl2=new Template(ROOT."templates/submenu_item.html");
	    $tpl2->hl = $request_uri!=$item['url'] ? "true" : "false";
		//print("reques: ".$request_uri."<br>");
		//print("Item: ".$item['url']."<hr>");

	    $tpl2->id=$item['id'];
	    $tpl2->title=$lang->getText($item['title']);
	    if($inbox&&$item['title']=='T_INBOX')$tpl2->title.=" ($inbox)";
	    $tpl2->pageurl=ltrim($item['url'],'/');
	    $tpl->submenu.=$tpl2->output();
	    $page=$item['parent_id'];
	}
  	//create menu
  	$menu->setUrl(null);
  	$menu->setParent_Id(0);
	$menu->load();
	$tpl->menu="";
  	$subid=0;

  	while($item=$menu->next()){
  		DAO::connect();
	    $tpl2=new Template(ROOT."templates/menu_item.html");

	    if($item['url']==''||$item['url']=='index.php')$home=$item['id'];

	    if(@$_REQUEST['m'] == 'video' && $request_v != 0){
	    	DAO::connect();  	

	    	$strSQL = "SELECT categories_id, parent_id   
	    	FROM videos vid LEFT JOIN categories cat 
	    	ON vid.categories_id = cat.id 
	    	WHERE vid.id = $request_v  ";

	    	$qry = mysql_query($strSQL);

	    	if(mysql_num_rows($qry) > 0){

				$row = mysql_fetch_array($qry);
				$request_c = ($row['parent_id'] != 0) ? $row['parent_id'] : $row['categories_id'];
	    	}
	    }
	    //$this->_compare_uris($request_uri,$item['url'])

	    $request_uri_parent = ($request_c_parent != 0) ? str_replace('c='.$request_c, 'c='.$request_c_parent, $item['url']) : $item['url']; 

	    if($page==$item['id'] || $request_uri == $item['url'] || (eregi('c='.$request_c_parent, $request_uri_parent) || eregi('c='.$request_c, $item['url']) && !empty($request_c))){
		//SI $tpl->h1 == "true" destacar� el item del men� (canal seleccionado)
			if($page==0)$subid=$item['id'];
		 	 $tpl2->hl = "true";
		} else {
		 	 $tpl2->hl = "false";
		}

		
  		$tpl2->id=$item['id'];
	    $tpl2->title=$lang->getText($item['title']);
	    if($inbox && $item['title'] == 'T_INBOX')$tpl2->title.=" ($inbox)";
	    $tpl2->pageurl=ltrim($item['url'],'/');
	    $tpl->menu.=$tpl2->output();
	}

	//create sub menu (if submenu item is not selected)

	if($page==0){
		if($subid==0)$subid=@$home;
		$menu->setParent_Id($subid);
		$menu->load();
		$tpl->submenu="";

		while($item=$menu->next()){
		    $tpl2=new Template(ROOT."templates/submenu_item.html");
		    $tpl2->hl = $request_uri==$item['url'] ? "true" : "false";
		    $tpl2->id=$item['id'];
		    $tpl2->title=$lang->getText($item['title']);

		    if($inbox&&$item['title']=='T_INBOX')$tpl2->title.=" ($inbox)";
		    $tpl2->pageurl=ltrim($item['url'],'/');
		    $tpl->submenu.=$tpl2->output();
		}
	}
	$blnShowSWF = "false";

	
	//create footer menu
  	$menu->setParent_Id(0);
  	$menu->setHeader(null);
  	$menu->setFooter(1);
	$menu->load();

	$tpl->footer_menu='';

	

	while($item=$menu->next()){
	    $tpl2=new Template(ROOT."templates/footer_menu_item.html"); 
	    $tpl2->title=$lang->getText($item['title']);
	    if($inbox&&$item['title']=='T_INBOX')$tpl2->title.=" ($inbox)";
	    $tpl2->pageurl=ltrim($item['url'],'/');
	    $tpl->footer_menu.=$tpl2->output();
	}
	
  	//set variables

   	$this->SetAllRequestItems();


	$tpl->title=$this->title?$this->title:'';
	$tpl->titleheader=!empty($this->title) ?  " - " .$this->title : '';
	$tpl->content=$this->content;
	$tpl->keywords=$this->keywords;
	$tpl->description=!empty($this->description)?$this->description:SITENAME;
	$tpl->channel=$this->req_c;
	

	$tpl->url=URL;
	$tpl->sitename=SITENAME;
	$tpl->username=$this->username;
	$tpl->request_uri=trim(substr($request_uri,1));
	//se generar� el html, no hay mas queries, podemos cerrar cualquier conexi�n que haya quedado abierta 
	@mysql_close();
  	return $tpl->output();
  }

  private function _compare_uris($a, $b){
  	$a=str_replace('index.php?m=','',$a);
  	$b=str_replace('index.php?m=','',$b);
  	if($a==$b){
  		return true;
  	}else{
  		$c=strlen($a);
  		$d=substr($b,0,-3);
  		for($i=0; $i<$c; $i++){
  			if(substr($a,0,$c-$i)==$d) return true;
  		}
  		return false;
  	}
  }
}
?>