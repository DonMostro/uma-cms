<?php
include_once("root.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/PageCtrl.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/admin/controller.php");

class Summary {
  private $models;
  private $alerts;

  function Summary(){
    $this->models=array();
    $this->alert=array();
  }

  function addAlert($model,$value){
  	$this->alerts[$model]=$value;
  }

  function addModel($model, $name, $url){
  	$this->models[]=array('object'=>$model,
  			      		  'name'=>$name,
						  'url'=>$url);
  }

  function display(){
	$out = "<h2>Estad&iacute;sticas</h2>\r\n";
	$out .= "<table>\r\n";
    foreach ($this->models as $model){
    	$out.="<tr class=\"trow\">";
    	$model['object']->load();
    	$values=$model['object']->next();
    	$value=array_pop($values);
    	if(isset($this->alerts[$model['name']])&&$this->alerts[$model['name']]!=$value){
    		$image="<img src=\"images/error.png\" />";
    	} else {
    		$image="";
    	}
    	$out.="<td><a style=\"font-size:11pt\" href=\"{$model['url']}\">$image {$model['name']}</a></td><td><a style=\"font-size:11pt\" href=\"{$model['url']}\">$value</a></td>";
    	$out.="</tr>\r\n";
    }
	$out .= "</table>\r\n";
    return $out;
  }
}
?>