<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");

include_once(ROOT."classes/lib/DAO.php");

include_once(ROOT."classes/lib/Form.php");



class xmllist extends Element{

  

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

      $dirs=glob(ROOT.$this->params['PATH'].'/*.xml');

      $selected=array();

      

      $request=new Form();

      if($this->value==null){

      	if(isset($request->{$this->target}))$value=$request->{$this->target};

      	else $value=null;

      }else{

      	$value=$this->value;

      }

      

	  foreach($dirs as $dir){

	  	$basename=pathinfo($dir,PATHINFO_BASENAME);

	  	$dir=substr($basename,0,-4);

        $selected[$dir] = $dir==$value ? "selected" : "";

	    $options.="<option value=\"{$dir}\" {$selected[$dir]} >{$dir}</option>\r\n";

	  }

	  return $options;

  }

  

}

?>