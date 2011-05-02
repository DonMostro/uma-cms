<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/models/MSettings.php");
//include_once(ROOT."classes/controllers/CUser.php");
//include_once(ROOT."classes/lib/acl.php");
include_once(ROOT."classes/views/VPage.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Settings.php");
//include_once(ROOT."classes/lib/Permissions.php");
//include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/lib/ModelPool.php");
include_once(ROOT."classes/lib/Debug.php");

/**
 * Clase base para objetos controladores. Cada comando esta seteado en el request m
 * y busca su correspondiente controlador en map.php
 * 
 */
class CCommand {
  /**
   * Vista maestra
   *
   * @var VPage
   */
  public $page;
  /**
   * Variables de request
   *
   * @var Form
   */

  protected $form;
  /*
   * Active user information. Check for $this->user->username to see if one is logged in
   *
   * @var CUser
   */
/*
  protected $user;
*/
  /**
   * El comando padre.
   *
   * @var CCommand
   */

  protected $parent;

  /**
   * The generated content for the current user request, to be insrted into the master view
   * or returned directly.
   *
   * @var string
   */

  public $content;

  /**
   * Application settings
   *
   * @var Settings
   */

  protected $settings;

  /*
   * The language string builder. Use $this->lang->getText('LANG_STRING') to get
   * language strings.
   *
   * @var Lang
   */
/*
  protected $lang;
*/
  /*
   * The required permissions to access certain resources. Compare it to $this->user->permissions
   *
   * @var Permissions
   */
/*
  protected $permissions;
*/
  /**
   * Model pool. 
   *
   * @var ModelPool
   */

  protected $models;

  /*
   * Response handler.
   *
   * @var v_HttpResponse
   */
/*
  protected $response;
*/
  protected $children;

  /**
   * Constructor
   * @param $parent
   */
  
  function CCommand($parent=null){
    $this->form=Form::getInstance();
    //$this->lang=Lang::getInstance();
	//$this->user=CUser::getInstance();
	$this->page=VPage::getInstance();
	$this->models=ModelPool::getInstance();
	
	$this->parent=$parent;
	if($parent!=null){
		$this->parent->addChild($this);
	}
	$this->children=array();
	//set logged in username
	//$this->page->username=$this->user->username;
	//load settings
	$settings=Settings::getInstance();
	$this->settings=$settings->getSettings();

	//load permissions
	//$permissions=Permissions::getInstance();
	//$this->permissions=$permissions->getPermissions();
  }
  public function addChild($child){
  	$this->children[]=$child;
  }
  /**
   * Retorna el contenido final a mostrar. Este m&eacute;todo puede ser sobreescripto 
   * para saltarse la vista maestra
   * 
   * Si encuentra el request ajax 
   * retorna la salida directamente, saltando la vista maestra. 
   *
   * @return The output content
   */
  public function show(){
		$this->run();
  		if($this->parent!=null){
			$this->parent->show();
			$this->content=$this->parent->content;
		}
		
		  
		
		if(count($this->children)==0){
			if(isset($this->form->ajax)){
				return $this->content;
			}else{
				$this->page->content=$this->content;
				return $this->page->show();
			}
		}
  }
  /**
   * Ejecuta el comando y prepara la salida para la vista maestra.
   *
   */

  public function run(){

  }
}

?>