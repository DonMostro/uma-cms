<?php
include_once("root.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/lib/PageCtrl.php");

/**
 * Base de clase de vista para mostrar los tipos de recolecci�n de datos. Las clases heredadas implementa
 decorate_list * () y decorate_item () m�todos. La idea principal de estos m�todos (y
 * La capa de la Vista en general) es crear las etiquetas para las plantillas y los inserta en
 * Las plantillas seleccionadas. Los m�todos devuelven el contenido generado.


 * 
 * decorate_list () y decorate_item () pueden ser omitido. En ese caso,
 * Filter () se utiliza para decorar los elementos que deben ser procesados en lugar de ir a buscar a la
 * Plantilla directamente desde el modelo.

 *
 */
class VCollection extends VView{
  
  protected $model;  
  public $empty_msg;
  public $tplitemfile;
  
  function VCollection($model){
     $this->model=$model;
     $this->empty_msg="Sin videos";
     parent::__construct();
  }

   /**
   * Rellene la plantilla con los datos.
   *
   * @return string Parsed template.
   */
  public function show(){
    $list="";
    
    //Get lista del modelo y decorar    
    if($this->model&&$this->model->getSize()>0){
        for($i=0; $i<$this->model->getSize(); $i++){
            $info=$this->model->next();
            $list.=$this->decorate_item($info);
        }
    }else{
        $list=$this->empty_msg;
    }

	//Decora la lista y volver	
    return $this->decorate_list($list);

  } 
  
  /**
   * Llene la plantilla de elementos.
   *
   * @param mixed $info Los datos que se inserta en la plantilla.
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
   * Llene la plantilla principal
   *
   * @param string $list El conjunto de elemento de plantillas preparadas.
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
   * Filtro de los valores de la plantilla. Para ser utilizado cuando los datos deben ser procesados antes de insertarse en la plantilla.

   *
   * @param Template $tpl
   */
  protected function filter(Template $tpl){
  	
  }


}

?>