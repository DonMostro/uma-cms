<?php
ini_set("display_errors","On");
error_reporting(E_ALL);
include_once("root.php");
include_once(ROOT."classes/admin/element.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/models/MTypes.php");

class multiconversion extends Element{
  protected $visible;
  protected $edit;
  protected $name;
  protected $target;
  protected $value;
  protected $params;
  protected $html;

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
  	$model->load();
  	
  	while($model->next()){
  		echo "X";
  		/*$view=new VView($model);
  		$view->show();
  		
  		if($this->value == ''){
			//$link = $this->params['LINK1'];
			$image = "<img src=\"".ROOT.FILES_IMAGES."/".$view->data['thumb']."\" title=\"Iphone Convert\"/>";
  		}else{
  			//$link = $this->params['LINK2'];
  			$image = "<img src=\"".ROOT.FILES_IMAGES."/".$view->data['thumb']."\" title=\"Iphone Video Delete\"/>";
  		}
  		*/
		$href=str_replace("{id}",$this->params['ID'],$link);
		$href=str_replace("{value}",$this->value,$href);
		$this->html.= "<a id=\"field{$i}_{$j}\" title=\"Desasignar Video Iphone\" name=\"$this->target[]\" href=\"$href\">$image</a>";  	   
		
  	}	
	return $this->html;
  }
}
?>