<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");

include_once(ROOT."classes/lib/DAO.php");

include_once(ROOT."classes/lib/Form.php");



class select extends Element{

  

  function edit($i, $j, $display="inline"){

	  $options=$this->options();

	  return "<select style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"{$this->target}[$i]\">\r\n$options\r\n</select>";

  }

  

  function display($i, $j){

      $options=$this->options();



      return "<select disabled=\"disabled\" id=\"field{$i}_{$j}\">\r\n$options\r\n</select>";

  }

  

  function options(){

  	  $options="<option value=\"0\">* None *</option>\r\n";

      $dao=new DAO();

      $id=!empty($this->params['TABLEPK'])?$this->params['TABLEPK']:'id';

      $dao->query("SELECT `{$this->params['FIELD']}`,`$id` FROM {$this->params['TABLE']}");

      $selected=array();

      

      $request=new Form();

      if($this->value==null){

      	if(isset($request->{$this->target}))$value=$request->{$this->target};

      	else $value=null;

      }else{

      	$value=$this->value;

      }

      

	  while($row=$dao->getAll()){

        $selected[$row[$id]] = $row[$id]==$value ? "selected" : "";

	    $options.="<option value=\"".$row[$id]."\" ".$selected[$row[$id]]." >{$row[$this->params['FIELD']]}</option>\r\n";

	  }

	  return $options;

  }

  

}

?>