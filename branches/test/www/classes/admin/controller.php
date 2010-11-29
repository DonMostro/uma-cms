<?php

include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Form.php");

class Controller{
  var $layout;
  var $page;
  var $id;
  var $name;
  var $target;
  var $maxsize;

  function Controller($page,$id=array()){
    $this->layout=array();
    $this->page=$page;
    $this->id=$id;
    $this->requested_params="";
  }

  function setId($id){
  	$this->id=$id;
  }

  function getLayout(){
    $xml=new XML();
    $xml->parse("xml/".$this->page.".xml");
    $this->layout=$xml->elements;
    $this->name=$this->layout[0]['NAME'];
    $this->target=$this->layout[0]['TARGET'];
    $count=count($this->layout);
    for($i=1; $i<$count; $i++){
	  	$this->layout[$i]["VISIBLE"]=$this->layout[$i]["VISIBLE"]=="true"?true : false;
	    $this->layout[$i]["EDIT"]=$this->layout[$i]["EDIT"]=="true"?true : false;
	    $this->layout[$i]["ADD"]=$this->layout[$i]["ADD"]=="true"?true : false;
	}
  }

  function getData($start=0,$limit=20,$search="",$sort="",$dir="ASC"){
  	$form=new Form();
    $model=$this->layout[0]["TARGET"];
    include_once(ROOT."classes/models/$model.php");
    $modelobj=new $model();

    //Prepare the model
    $modelobj->setId($this->id);
    if($sort!="")$modelobj->addOrder(new DataOrder($sort,$dir));
    if(empty($this->id))$modelobj->setStart($start);
    $modelobj->setLimit($limit);
    if(method_exists($modelobj,"setSearch"))$modelobj->setSearch($search);

    
    //Get request parameters for the data fields
    $count=count($this->layout);
    for($i=1; $i<$count; $i++){
    	$field=$this->layout[$i]["TARGET"];
    	if(isset($form->$field) && !is_array($form->$field) && $form->$field!==""){
    		$method="set$field";
    		$modelobj->$method($form->$field);
    	}
    }

    $modelobj->load();
    //Fetch data
    while($row=$modelobj->next()){
	  for($i=1; $i<$count; $i++){
	  	  if(isset($row[$this->layout[$i]["TARGET"]]))
		  	$this->layout[$i]["VALUE"][]=$row[$this->layout[$i]["TARGET"]];
		  elseif (isset($this->layout[$i]["DEFAULT"]))$this->layout[$i]["VALUE"][]=$this->layout[$i]["DEFAULT"];
		  else $this->layout[$i]["VALUE"][]="";
	  }
	}
	$this->maxsize=$modelobj->countAll();
  }

  function getRequested_params(){ 
  	$form=new Form();
  	$count=count($this->layout);
  	$params="";

    for($i=1; $i<$count; $i++){
    	$field=$this->layout[$i]["TARGET"];
    	if(isset($form->$field)){
    		if(is_array($form->$field)){
    			$params.="&$field=".urlencode($form->{$field}[0]);
    		}else{
    			$params.="&$field=".urlencode($form->$field);
    		}
    	}
    }

  	return $params; 
  }
}
?>