<?php

include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/ModelPool.php");
include_once(ROOT."classes/admin/controller.php");
include_once(ROOT."classes/admin/elements_inc.php");

/**
 * Tabla HTML de la edición de elementos del CMS
 *
 */

class EditTable extends Controller{
  function display($mode='EDIT'){
  	$form=new Form();
    $out="<table>";
    $count=count($this->layout);
    if(!isset($this->id))$this->id=array();
    elseif(!is_array($this->id))$this->id=array($this->id);
    if(!isset($this->layout[1]['VALUE']))$this->layout[1]['VALUE']=array("");
	$vcount=count($this->layout[1]['VALUE']);
	for($i=0; $i<$vcount; $i++){
	  if(in_array($this->layout[1]['VALUE'][$i],$this->id)||$vcount==1){
		  for($j=1; $j<$count; $j++){
		    $node=$this->layout[$j];
		    $params=array();
		    foreach($node as $k=>$v)if($k!='VALUE')$params[$k]=$v;
		    if(!isset($node['VALUE'][$i]))$node['VALUE'][$i]="";
		    if(!empty($node['VALUE'][$i]) || isset($form->{$node['TARGET']}) && is_array($form->{$node['TARGET']})){
		    	$value=$node['VALUE'][$i];
		    }else{
		    	$value=isset($form->{$node['TARGET']})?$form->{$node['TARGET']}:'';
		    }
			$element=new $node['TYPE']($node['VISIBLE'],$node['EDIT'],$node['NAME'],$node['TARGET'],$value,$params);
			if($node[$mode]){
				if($mode=='ADD')$pfx="_add";
				else $pfx="";
				$out.="<tr><td>{$node['NAME']}:</td><td>".$element->edit($i,$pfx.$j)."</td></tr>";
			}
		  }
		  $out.="<tr><td>&nbsp;</td></tr>";
	  }
	}
	$out.="</table>";
	return $out;
  }

  function edit(){
    $form=new Form();
    $count=count($this->id);
    $ecount=count($this->layout);
    include_once(ROOT."classes/models/{$this->target}.php");

    $mpool=ModelPool::getInstance();
    $model=&$mpool->getModel($this->target);
    $ret=false;

    for($i=0; $i<$count; $i++){
      $tmp=each($this->id);
      $k=$tmp['key']; //obtener el id real del form


	  for($j=1; $j<$ecount; $j++){
        if($this->layout[$j]['EDIT']&&$this->layout[$j]['TYPE']!="idbox"){
          $node=$this->layout[$j];
		  $params=array();
		  foreach($node as $l=>$v)if($l!='VALUE')$params[$l]=$v;
          $e=new $this->layout[$j]['TYPE'](false,false,"",$this->layout[$j]['TARGET'],null,$params);          
          if(isset($form->{$this->layout[$j]['TARGET']}) && isset($form->{$this->layout[$j]['TARGET']}[$k])){
          	$value=$form->{$this->layout[$j]['TARGET']}[$k];
          } else {
          	$value="";
          }
          $v=$e->get($value,$k);

          if((string)$v!='{no-change}'){
			  $method="set".$this->layout[$j]['TARGET'];
			  $model->$method($v);
          }
		}
	  }

	  if(isset($this->id[$k])){
	  	$model->setId($this->id[$k]);
	  	$ret=$model->update();
	  }
	}
	return $ret;
  }

  

  function add(){

    $form=new Form();

    $ecount=count($this->layout);

	include_once(ROOT."classes/models/{$this->target}.php");

    $mpool=ModelPool::getInstance();

    $model=&$mpool->getModel($this->target);

	for($i=1; $i<$ecount; $i++){

		if($this->layout[$i]['ADD']&&$this->layout[$i]['TYPE']!="idbox"){

	      $e=new $this->layout[$i]['TYPE'](false,false,"",$this->layout[$i]['TARGET']);

	      $v=isset($form->{$this->layout[$i]['TARGET']})?$e->get($form->{$this->layout[$i]['TARGET']}[0],0):"";

	      if($v=="{no-change}")$v="";

		  $method="set".$this->layout[$i]['TARGET'];

		  $model->$method($v);

		}

	
	}
	$model->add();
  }

  

  function delete(){
    include_once(ROOT."classes/models/{$this->target}.php");
    $mpool=ModelPool::getInstance();
    $model=$mpool->getModel($this->target);
    $model->setId($this->id);
	$model->delete();
  }

}

?>