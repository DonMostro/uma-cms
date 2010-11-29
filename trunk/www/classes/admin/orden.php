<?php
include_once("root.php");
include_once(ROOT."classes/admin/element.php");

class orden extends Element{
  var $Options;		
  
  function edit($i, $j, $display="inline"){
  	  $selected=array(1=>'','','','','','','','','','','','','','','','','','','');
  	  if($this->value=='2'){
  	  	$selected[2] = "selected=\"selected\"";
  	  }elseif($this->value=='3'){
  	  	$selected[3] = "selected=\"selected\"";
  	  }elseif($this->value=='4'){
  	  	$selected[4] = "selected=\"selected\"";	
  	  }elseif($this->value=='5'){
  	  	$selected[5] = "selected=\"selected\"";
  	  }elseif($this->value=='6'){
  	  	$selected[6] = "selected=\"selected\"";
  	  }elseif($this->value=='7'){
  	  	$selected[7] = "selected=\"selected\"";
  	  }elseif($this->value=='8'){
  	  	$selected[8] = "selected=\"selected\"";
  	  }elseif($this->value=='9'){
  	  	$selected[9] = "selected=\"selected\"";	
  	  }elseif($this->value=='10'){
  	  	$selected[10] = "selected=\"selected\"";
  	  }elseif($this->value=='11'){
  	  	$selected[11] = "selected=\"selected\"";
  	  }elseif($this->value=='12'){
  	  	$selected[12] = "selected=\"selected\"";
  	  }elseif($this->value=='13'){
  	  	$selected[13] = "selected=\"selected\"";
  	  }elseif($this->value=='14'){
  	  	$selected[14] = "selected=\"selected\"";	
  	  }elseif($this->value=='15'){
  	  	$selected[15] = "selected=\"selected\"";
  	  }elseif($this->value=='16'){
  	  	$selected[16] = "selected=\"selected\"";
  	  }elseif($this->value=='17'){
  	  	$selected[17] = "selected=\"selected\"";
      }elseif($this->value=='18'){
  	  	$selected[18] = "selected=\"selected\"";
  	  }elseif($this->value=='19'){
  	  	$selected[19] = "selected=\"selected\"";
  	  }elseif($this->value=='20'){
  	  	$selected[20] = "selected=\"selected\"";	  	
  	  }else{
  	  	$selected[1] = "selected=\"selected\"";
  	  }
  	  $strOptions = "<select style=\"width:50px;display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\">
  	  ";
  	 
  	  $strSQL = "SELECT id FROM {$this->params['TABLE']} " ;
  	  //if($this->params['TABLE'] == 'top_carrusel') $strSQL .= " WHERE videos_id <> 0 ";
  	  
  	  
  	  
  	  $lngOptions = mysql_num_rows(mysql_query($strSQL));

  	  for($i=1;$i<=$lngOptions+5;$i++){
  	  	$strOptions .= "<option value=\"$i\"". @$selected[$i].">$i</option>";
  	  }
  	  $this->Options = $lngOptions;
  	  return $strOptions;
  }
  
  function display($i, $j){
	$status = ctype_digit($this->value) && $this->value != '' ? $this->value : 1;	
	  return "<span id=\"field{$i}_{$j}\" >$status</span>";
  }
  
  function get($value){
    return $value;
  }
  
}
?>