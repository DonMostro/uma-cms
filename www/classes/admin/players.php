<?php
include_once("root.php");
include_once(ROOT."classes/admin/element.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/models/MPlayers.php");

/**
 * Elemento Categor&iacute;as en CMS
 * @author Rodrigo
 *
 */

class players extends Element{
  /**
   * (non-PHPdoc)
   * @see www/classes/admin/Element#edit($i, $j, $display)
   */
  function edit($i, $j, $display="inline"){
	  $options=$this->options();
	  return "<select style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"{$this->target}[$i]\">\r\n$options\r\n</select>";
  }
  /**
   * (non-PHPdoc)
   * @see www/classes/admin/Element#display($i, $j)
   */
  function display($i, $j){
      $options=$this->options();
	  $edit="";
      if($this->edit)$edit=$this->edit($i,$j,"none");
      return "<select disabled=\"disabled\" id=\"field{$i}_{$j}\">\r\n$options\r\n</select>".$edit;
  }

  /**
   * Options del select de categor&iacute;as
   * @return string HTML
   */
  function options(){
        $options="<option value=\"0\">* Ninguna *</option>\r\n";
        $model=new MPlayers();
        $model->load();
        while($t=$model->next()){
          $selected=$this->value==$t['title']?'selected="selected"':'';
			$options.="<option value=\"".$t['title']."\" $selected >".$t['title']."</option>\r\n";
		}
        return $options;
  }
}
?>