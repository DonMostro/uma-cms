<?php

include_once("root.php");

include_once(ROOT."classes/admin/element.php");

include_once(ROOT."classes/lib/DAO.php");

include_once(ROOT."classes/lib/Form.php");

include_once(ROOT."classes/models/MCategoryList.php");



class categories extends Element{

  

  function edit($i, $j, $display="inline"){

	  $options=$this->options();

	  return "<select style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"{$this->target}[$i]\">\r\n$options\r\n</select>";

  }

  

  function display($i, $j){

      $options=$this->options();

	  $edit="";

      if($this->edit)$edit=$this->edit($i,$j,"none");

      return "<select disabled=\"disabled\" id=\"field{$i}_{$j}\">\r\n$options\r\n</select>".$edit;

  }

  

  function options(){

        $options="<option value=\"0\">* None *</option>\r\n";

        $model=new MCategoryList();

        $model->load();

        $model->getLevels();

        while($c=$model->next()){

          $level='';

          for($i=0; $i<$c['level']; $i++)$level.='&nbsp; &nbsp; ';

          $selected=$this->value==$c['id']?'selected="selected"':'';

	  $options.="<option value=\"".$c['id']."\" $selected >".$level.$c['title']."</option>\r\n";

	}

        return $options;

  }

  

}

?>

