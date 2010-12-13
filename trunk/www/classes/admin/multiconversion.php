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
	
  	
  	while($t=$model->next()){
  		
  		if($this->value == ''){
			$image = "<img src=\"".ROOT.FILES_IMAGES."/".$t['thumb']."\" alt=\"".$t['title']."\" title=\"".$t['title']."\"/>";
  		}else{
  			$image = "<img src=\"".ROOT.FILES_IMAGES."/".$t['thumb']."\" alt=\"".$t['title']."\" title=\"".$t['title']." Borrar\"/>";
  		}
  		
		$href="javascript:popup('convert.php?types_id=".$t['id']."&videos_id=".$this->params['ID']."');";
		$this->html.= "<a id=\"field{$i}_{$j}\" title=\"".$t['title']."\" name=\"$this->target[]\" href=\"$href\" class=\"conversion\">$image</a>";  	   
		
  	}	
	return $this->html;
  }
}
?>