<?php

include_once("root.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/PageCtrl.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/admin/edittable.php");
include_once(ROOT."classes/admin/viewtable.php");

/**
 * Tabla HTML del CMS
 * @author Administrador
 *
 */

class Table{
  public $page;
  function Table($page){
    $this->page=$page;
  }

  function display(){
    $form=new Form();
    $request=array(); 
    foreach (get_object_vars($form) as $var=>$val){
    	$request[$var]=$val; 
    }

    $start=isset($request['start']) ? (int)$request['start'] : 0;
	$search=isset($request['search']) ? $request['search'] : "";
    $out = "<script type=\"text/javascript\">p='$this->page';\r\nstart=$start;\r\nsearch='".urlencode($search)."';\r\n";

    $i=0;
    foreach ($request as $param => $value){
    	if(!in_array($param,array('p','start','search','sort','dir'))&&!is_array($request[$param])){
    		$out.="params[".$i++."]='$param';\r\n";
    		$out.="values['$param']='".urlencode($value)."';\r\n";
    	}
    }
    $out.="</script>\r\n<div id=\"content\">\r\n";
    $edittable=new EditTable($this->page);
	$edittable->getLayout();
	$id=$edittable->layout[1]['TARGET'];
	if(isset($request[$id]))$edittable->setId($request[$id]);
	$a=isset($request['a'])?$request['a']:"";
	$viewtable=new ViewTable($this->page);
	$viewtable->getLayout();
	$location=str_replace('&a=add','',$_SERVER['REQUEST_URI']);
	$location=str_replace('&a=delete','',$location);
	$location=str_replace('&a=edit','',$location);
	$location=str_replace('id[]=','',$location);
	$location=str_replace('&ajax=1','',$location);
    switch($a){
	  case "edit":
	  	$start=isset($request['start']) ? (int)$request['start'] : 0;
	  	$search=isset($request['search']) ? $request['search'] : "";
	  	$edittable->getData($start,20,$search);

	  	if(!isset($request['save'])){
	  	  	$out .= "<h2>Editar {$edittable->name}</h2>";
		  	$out .= "<form action=\"$location&a=edit\" method=\"post\" enctype=\"multipart/form-data\">\r\n";
		  	$out .= $edittable->display('EDIT');
		  	$out .= "<input type=\"hidden\" name=\"save\" value=\"save\">\r\n";
		  	$out .= "<input type=\"submit\" value=\"Guardar\"  class=\"big-button\"/>";
		  	$out .= "</form>\r\n";
	  	}else{
			$response=$edittable->edit()?"1":"0";
			if(isset($request['ajax'])) return $response;
			else header("Location: $location");
		}
	  	break;
	  case "add":
	  	if(!isset($request['save'])){
	  	    $out .= "<h2>Agregar a {$edittable->name}</h2>";
		  	$out .= "<form action=\"$location&a=add\" method=\"post\" enctype=\"multipart/form-data\">\r\n";
			$out .= $edittable->display('ADD');
		  	$out .= "<input type=\"hidden\" name=\"save\" value=\"save\">\r\n";
		  	$out .= "<input type=\"submit\" value=\"Agregar\" class=\"big-button\"/>";
		  	$out .= "</form>\r\n";
	  	}else{
			$edittable->add();
			$ajax=isset($request['ajax'])?"&ajax=1":"";
			header("Location: ".$location.$ajax);
		}	  	
	    break;
	  case "delete":
	  	$edittable->delete();
	  	$ajax=isset($request['ajax'])?"&ajax=".$request['ajax']:"";
		header("Location: ".$location.$ajax);
	  	break;
	  default:
	  	$out .= "<h2>{$viewtable->name}</h2>\r\n";
	  	if(file_exists('parts/'.$this->page.'.php'))include 'parts/'.$this->page.'.php';
	  	$search="";
	  	$id=$viewtable->layout[1]['TARGET'];
	  	if(isset($viewtable->layout[0]['SEARCH'])&&$viewtable->layout[0]['SEARCH']=='true'){
		  	$out .= "<form class=\"search\" action=\"index.php\"><input type=\"hidden\" name=\"p\" value=\"{$this->page}\"/>\r\n";
			$search=isset($request['search']) ? $request['search'] : "";
		  	$out .= "Buscar: <input type=\"text\" name=\"search\" value=\"".htmlspecialchars($search)."\"/> <input type=\"submit\" value=\"ir\"/></form>\r\n";
	  	}
	  	$start=isset($request['start']) ? (int)$request['start'] : 0;
	  	$sort=isset($request['sort']) ? $request['sort'] : "";
	  	$dir=isset($request['dir']) ? $request['dir'] : "ASC";
	  	$viewtable->getData($start,20,$search,$sort,$dir);
	  	$count=$viewtable->maxsize;
	  	$search=isset($request['search'])?$request['search']:"";
	  	$text=isset($request['text'])?$request['text']:"";
	  	$pages=PageCtrl::getCtrl($count,$start,20,"index.php?p=$this->page&search=$search&text=$text&sort=$sort&dir=$dir".$viewtable->getRequested_params()."&ajax=1",true);
	  	$out.="<p class=\"line\">P&aacute;ginas: ".$pages."</p>";
	  	$out .= "<form name=\"frm1\" action=\"index.php?p={$this->page}\" method=\"post\" enctype=\"multipart/form-data\">\r\n";
	  	$out .= $viewtable->display();
	  	$out .= "<a href=\"javascript:void(0)\" onclick=\"select_all(frm1.elements['{$id}[]'])\">Select/Unselect All</a> <input type=\"hidden\" name=\"a\"/>";

	  	if(isset($viewtable->layout[0]['EDIT'])&&$viewtable->layout[0]['EDIT']=='true')$out .= " <input type=\"button\" onclick=\"check(frm1,'{$id}[]','edit')\" value=\"edit\"/> ";

	  	if(isset($viewtable->layout[0]['DELETE'])&&$viewtable->layout[0]['DELETE']=='true')$out .= "<input type=\"button\" onclick=\"if(confirm('¿Está Seguro?')==true){check(frm1,'{$id}[]','delete')}\" value=\"delete\"/>";

	  	if(isset($_REQUEST['start'])) $out .= "<input type=\"hidden\" name=\"start\" id=\"start\" value=\"".(int)$_REQUEST['start'] ."\" />";
	  	$out .= "</form>\r\n";
	  	$out.="<p class=\"line\">P&aacute;ginas: ".$pages."</p>";

	  	if(isset($viewtable->layout[0]['ADD'])&&$viewtable->layout[0]['ADD']=='true'){
	    	$out .= "\r\n<h3>Nuevo:</h3>\r\n";
		  	$out .= "<form action=\"index.php?p=$this->page\" name=\"frm2\" method=\"post\" enctype=\"multipart/form-data\">\r\n";
			$out .= $edittable->display('ADD');
		  	$out .= "<input type=\"hidden\" name=\"save\" value=\"save\" />\r\n";
		  	$out .= "<input type=\"button\" onclick=\"add('".$_SERVER['REQUEST_URI']."&a=add&ajax=1',frm2)\" value=\"Crear\" class=\"big-button\" /></form>";
	  	}
    }
    $out .= "</div>\r\n";
    return $out;
  }
}
?>