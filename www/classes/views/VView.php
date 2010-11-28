<?php
include_once("root.php");
include_once(ROOT."classes/lib/Template.php");
//include_once(ROOT."classes/lib/Lang.php");
//include_once(ROOT."classes/models/MAds.php");

/**
 * Clase base para las vistas. Capa entre modelos y templates.
 *
 */
class VView{
  
  public $tplfile;
  private $data;
  public $ads;
	
  function VView($model=null){
  	
  	if($model!==null){
  		$this->data=$model->next();
  	}
  	
  }
  /*
  public function _setAds(Template $tpl){
  	if(isset($this->ads)){
  		$ads = $this->ads;
  	}else{
  		$ads=new MAds();
  		$ads->setChannel('');
  	}
  	$ads->setApproved(1);
  	$ads->load();
  	if($ads->getSize()==0&&$this->ads){
	  	$ads->setChannel('');
	  	$ads->load();
  	}
  	while ($ad=$ads->next()){
	  	$tpl->{$ad['name']}=htmlspecialchars_decode($ad['code']);
	}
  }
  */
  /**
   * Carga la plantilla con los datos.
   *
   * @return string Parsed template.
   */
  public function show(){
  	if(!empty($this->tplfile)){
  		$tpl=new Template($this->tplfile);
  		
  		foreach(get_object_vars($this) as $p=>$var){
  			if(!isset($tpl->$p))$tpl->$p=$var;
  		}
  		
  		if(!empty($this->data)){
  			foreach($this->data as $p=>$var){
  				if(!isset($tpl->$p))$tpl->$p=$var;
  			}
  		}
  		
  		$this->filter($tpl);
  		
  		//$this->_setAds($tpl);
  		return $tpl->output();
  	}else{
  		return '';	
  	}
  }
  
  /**
   * Filtro de los valores de la plantilla. Para ser utilizado cuando los datos deben ser procesados antes de insertarse en la plantilla.
   *
   * @param Template $tpl
   */
  protected function filter(Template $tpl){
  	
  }

}
?>