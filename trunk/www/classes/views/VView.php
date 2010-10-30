<?php
include_once("root.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/models/MAds.php");

/**
 * Base class for the view. Provides advertisement facility and general template output by specified file.
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
  
  /**
   * Fill the template with data.
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
  		//ETIQUETAS GOOGLE
  		$VPage = new VPage();
  		$VPage->SetAllRequestItems();
  		include(ROOT."ads/googleChannels.php");
  		//FIN ETIQUETAS
  		
  		$this->filter($tpl);
  		
  		$this->_setAds($tpl);
  		return $tpl->output();
  	}else{
  		return '';	
  	}
  }
  
  /**
   * Filter the values of template. To be used when data should be processed before inserted into the template.
   *
   * @param Template $tpl
   */
  protected function filter(Template $tpl){
  	
  }

}
?>