<?php
class multiconversion extends Element{
  protected $visible;
  protected $edit;
  protected $name;
  protected $target;
  protected $value;
  protected $params;

  public function multiconversion($visible=false,$edit=false,$name="",$target="",$value="",$params=array()){
    $this->visible=$visible;
    $this->edit=$edit;
    $this->name=$name;
    $this->target=$target;
    $this->value=$value;
    $this->params=$params;
  }

  public function display($i,$j){
  	$model = new MTypes();
  	while($model->next()){
  		var_dump($model);
  	
  		if($this->value == ''){
			$link = $this->params['LINK1'];
			$image = "<img src=\"images/iphone.png\" title=\"Iphone Convert\"/>";
  		}else{
  			$link = $this->params['LINK2'];
  			$image = "<img src=\"images/no_iphone.png\" title=\"Iphone Video Delete\"/>";
  		}
		$href=str_replace("{id}",$this->params['ID'],$link);
		$href=str_replace("{value}",$this->value,$href);  	   
		return "<a id=\"field{$i}_{$j}\" title=\"Desasignar Video Iphone\" name=\"$this->target[]\" href=\"$href\">$image</a>";
  	}	

  }
}
?>