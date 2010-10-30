<?php
class Element{
  protected $visible;
  protected $edit;
  protected $name;
  protected $target;
  protected $value;
  protected $params;

  public function Element($visible=false,$edit=false,$name="",$target="",$value="",$params=array()){
    $this->visible=$visible;
    $this->edit=$edit;
    $this->name=$name;
    $this->target=$target;
    $this->value=$value;
    $this->params=$params;
  }

  public function display($i,$j){
	if(!empty($this->params['LINK'])){
		if(!empty($this->params['IMAGE'])){
			$value="<img src=\"{$this->params['IMAGE']}\" alt=\"$this->value\"/>";
		}else{
			$value=$this->value;
		}

		$href=str_replace("{id}",$this->params['ID'],$this->params['LINK']);
		$href=str_replace("{value}",$this->value,$href);
		return "<a id=\"field{$i}_{$j}\" title=\"$this->value\" name=\"$this->target[]\" href=\"$href\">$value</a>";
	}
	return "<span id=\"field{$i}_{$j}\" name=\"$this->target[]\">{$this->value}</span>";
  }

  public function edit($i,$j,$display="inline"){
  	$readonly = @$this->params['READONLY'] ? "readonly" : '';
	$disabled = @$this->params['DISABLED'] ? "disabled" : '';
  	return "<input type=\"text\" style=\"display:$display\" id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" value=\"".str_replace('"','&quot;',$this->value)."\" $readonly $disabled/>";
  }

  public function get($value,$i=0){
    return $value;
  }
}
?>