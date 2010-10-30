<?php
ini_set("display_errors","on");
error_reporting(E_ALL);
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/models/MSettings.php");
include_once(ROOT."classes/controllers/CUser.php");
include_once(ROOT."classes/lib/acl.php");
include_once(ROOT."classes/views/VPage.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Permissions.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/lib/ModelPool.php");
include_once(ROOT."classes/lib/Debug.php");

/**
 * The base class for command classes. Each command is a request handler, which is 
 * mapped to a m query param in map.php.
 * 
 * The command gives access to the resources such as user request (suprisingly 
 * enough named 'form', no idea why), session, response, etc.
 */
class CCommand {
  /**
   * The master view
   *
   * @var VPage
   */
  public $page;
  /**
   * The user request. I've no idea why it is called 'Form'
   *
   * @var Form
   */

  protected $form;
  /**
   * Active user information. Check for $this->user->username to see if one is logged in
   *
   * @var CUser
   */

  protected $user;

  /**
   * The parent command.
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

  /**
   * The language string builder. Use $this->lang->getText('LANG_STRING') to get
   * language strings.
   *
   * @var Lang
   */

  protected $lang;

  /**
   * The required permissions to access certain resources. Compare it to $this->user->permissions
   *
   * @var Permissions
   */

  protected $permissions;

  /**
   * Model pool. Currently only used to dispatch the email notices upon certain events
   * in business objects.
   *
   * @var ModelPool
   */

  protected $models;

  /**
   * Response handler.
   *
   * @var v_HttpResponse
   */

  protected $response;

  protected $children;

  function CCommand($parent=null){
    $this->form=Form::getInstance();
    $this->lang=Lang::getInstance();
	$this->user=CUser::getInstance();
	$this->page=VPage::getInstance();
	$this->models=ModelPool::getInstance();
	
	/* Ya no es necesario HttpResponse para continuar (posible validación del CMS con Codemight) */
	//$this->response=v_HttpResponse::getInstance();
	
	$this->parent=$parent;
	if($parent!=null){
		$this->parent->addChild($this);
	}
	$this->children=array();
	//set logged in username
	$this->page->username=$this->user->username;
	//load settings
	$settings=Settings::getInstance();
	$this->settings=$settings->getSettings();

	//load permissions
	$permissions=Permissions::getInstance();
	$this->permissions=$permissions->getPermissions();
  }
  public function addChild($child){
  	$this->children[]=$child;
  }
  /**
   * Returns the final content to be displayed. This method can be overriden to skip
   * master view.
   * 
   * In addition to that, it looks for the 'ajax' parameter in the request query and 
   * if found, returns the output directily, skipping the master view. 
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
   * Executes the command and prepares the output to be inserted to the master view.
   *
   */

  public function run(){

  }
}

?>