<?php
class cellphone_element extends Element{
  protected $visible;
  protected $edit;
  protected $name;
  protected $target;
  protected $value;
  protected $params;

  public function cellphone_element($visible=false,$edit=false,$name="",$target="",$value="",$params=array()){
    $this->visible=$visible;
    $this->edit=$edit;
    $this->name=$name;
    $this->target=$target;
    $this->value=$value;
    $this->params=$params;
  }

  public function display($i,$j){
  	if($this->value == ''){
		$link = $this->params['LINK1'];
		$image = "<img src=\"images/cellphone.png\" title=\"3gp Video Convert\"/>";
  	}else{
  		$link = $this->params['LINK2'];
  		$image = "<img src=\"images/no_cellphone.png\" title=\"3gp Video Delete\"/>";
  	}
	$href=str_replace("{id}",$this->params['ID'],$link);
	$href=str_replace("{value}",$this->value,$href);  	   
	return "<a id=\"field{$i}_{$j}\" title=\"Desasignar Video 3gp\" name=\"$this->target[]\" href=\"$href\">$image</a>";

  }
}
?>