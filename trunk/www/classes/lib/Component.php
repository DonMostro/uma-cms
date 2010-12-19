<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/Form.php");

class Component {
	
  private $file;
  private $name;
  private $content;
  public $username;
	
  function __construct($file){
    $this->file=$file;
    $this->content;
  }
  
  public function run(){
  	$doc=simplexml_load_file($this->file);
  	if (!$doc) {
		Debug::write('Error al parsear componente: no se pudo abrir archivo '.$this->file);
	}else{
		$this->name=(string)$doc['name'];
		
		//if($lang->getText($this->name))$this->name=$lang->getText($this->name);
		
		if($doc->model){
			$model_name=(string)$doc->model['name'];
			$model_order=(string)$doc->model->order;
			if($model_order){
				$model_order_by=preg_replace_callback('/\$\w+\b/i', array($this, '_replace_var'), (string)$doc->model->order);
				$model_order_by=preg_replace_callback('/\%\w+\b/i', array($this, '_replace_param'), $model_order_by);
				$model_order_dir=(string)$doc->model->order['dir'];
				//$model_order_by=(string)$doc->model->order;
			}
			$m_params=array();
			foreach($doc->model->children() as $param){
				if($param->getName()!='order')$m_params[$param->getName()]=(string)$param;
			}
		}
		
		if($doc->view){
			$view_name=(string)$doc->view['name'];
			$v_params=array();
			foreach($doc->view->children() as $param){
				$v_params[$param->getName()]=(string)$param;
			}
		}
		
		if($model_name&&$view_name){
			$model=null;
			if(file_exists(ROOT.'classes/models/'.$model_name.'.php')){
				include_once(ROOT.'classes/models/'.$model_name.'.php');
				if(class_exists($model_name)){
					$model=new $model_name;
				}
			}
			if($model&&isset($model_order_by)){
				$dir=$model_order_dir?$model_order_dir:'DESC';
				$model->addOrder(new DataOrder($model_order_by,$dir));
			}
			if($model){
				$form=Form::getInstance();
				foreach($m_params as $name=>$param){
					/*if(substr($param,0,1)=='$'){
						$p=substr($param,1);
						$model->{'set'.$name}($this->$p);
					}elseif(substr($param,0,1)=='%'){
						$p=substr($param,1);
						$model->{'set'.$name}($form->$p);
					}else{
						$model->{'set'.$name}($param);
					}*/
					$param=preg_replace_callback('/\$\w+\b/i', array($this, '_replace_var'), $param);
					$param=preg_replace_callback('/\%\w+\b/i', array($this, '_replace_param'), $param);
					$model->{'set'.$name}($param);
				}
				$model->load();
			}
			if(file_exists(ROOT.'classes/views/'.$view_name.'.php')){
				include_once(ROOT.'classes/views/'.$view_name.'.php');
				if(class_exists($view_name)){
					$view=new $view_name($model);
				}
			}
			if(@$view){
				$view->title=$this->name;
				foreach($v_params as $name=>$param){
					/*foreach(get_object_vars($this) as $key => $val){
						$view->$name=str_replace('$'.$key,$val,$param);
					}*/
					if($name=='modify'){
						$acl=new ACL($this->username,$param);
						if($acl->canModify()){
							$view->modify='true';
						}
					}else{
						$view->$name=preg_replace_callback('/\$\w+\b/i', array($this, '_replace_var'), $param);
						$view->$name=preg_replace_callback('/\%\w+\b/i', array($this, '_replace_param'), $view->$name);
						//if(!empty($view->$name)&&$lang->getText($view->$name))$view->$name=$lang->getText($view->$name);
					}
				}
				$this->content=$view->show();
			}
		}
	}
  }
  
  private function _replace_var($m){
  	if(isset($m[0])){
  		$name=ltrim($m[0],'$');
  		if(isset($this->$name)){
  			return $this->$name;
  		}else{
  			return '';
  		}
  	} else{
  		return '';
  	}
  }
  
  private function _replace_param($m){
  	if(isset($m[0])){
  		$form=Form::getInstance();
  		$name=ltrim($m[0],'%');
  		if(isset($form->$name)){
  			return $form->$name;
  		}else{
  			return '';
  		}
  	} else{
  		return '';
  	}
  }
  
  public function show(){
  	return $this->content;
  }

  
}