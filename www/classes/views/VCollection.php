<?php
include_once("root.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/lib/PageCtrl.php");

/**
 * Base view class to display the collection data types. The inherited classes implements
 * decorate_list() and decorate_item() methods. The main idea for these methods (and
 * the View layer in general) is to create tags for the templates and insert them into
 * the selected templates. The methods return the generated content.
 * 
 * Since version 3.0, decorate_list() and decorate_item() can be omitted. In that case,
 * filter() is used to decorate items that must be processed rather than fetching to the
 * template straight from the model.
 *
 */
class VCollection extends VView{
  
  protected $model;  
  public $empty_msg;
  public $tplitemfile;
  
  function VCollection($model){
     $this->model=$model;
     $lang=Lang::getInstance();
     $this->empty_msg=$lang->getText('E_NOVIDEOS');
     parent::__construct();
  }

   /**
   * Fill the template with data.
   *
   * @return string Parsed template.
   */
  public function show(){
    $list="";
    
    //Get list from the model and decorate it
    if($this->model&&$this->model->getSize()>0){
        for($i=0; $i<$this->model->getSize(); $i++){
            $info=$this->model->next();
            $list.=$this->decorate_item($info);
        }
    }else{
        $list=$this->empty_msg;
    }

	//Decorate list and return
    return $this->decorate_list($list);

  } 
  
  /**
   * Fill the item template.
   *
   * @param mixed $info Data to be inserted into template.
   * @return string Parsed template.
   */
  protected function decorate_item($info){ 
  	if(!empty($this->tplitemfile)){
  		$tpl=new Template($this->tplitemfile);
  		foreach ($info as $name=>$value){
  			$tpl->$name=$value;
  		}
  		$this->filter($tpl);
  		return $tpl->output();
  	}else{
  		return '';
  	}
  }

  /**
   * Fill the main template
   *
   * @param string $list The set of prepared item templates.
   * @return string Parsed template.
   */
  protected function decorate_list($list){ 
    if(!empty($this->tplfile)){
  		$tpl=new Template($this->tplfile);
  		
  		foreach(get_object_vars($this) as $p=>$var){
  			if(!isset($tpl->$p))$tpl->$p=$var;
  		}
  		$this->_setAds($tpl);
  		$tpl->list=$list;
  		$url=!empty($this->url) ? $this->url : URL.'?';
  		$tpl->pagination=PageCtrl::getCtrl($this->model->countAll(),$this->model->getStart(),$this->model->getLimit(),$url."&amp;sort=".$this->model->getOrder());
  		return $tpl->output();
  	}else{
  		return $list;	
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