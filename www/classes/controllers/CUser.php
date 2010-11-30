<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/PageCtrl.php");
include_once(ROOT."classes/lib/Session.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/UserIntegrate.php");
include_once(ROOT."classes/models/MUser.php");
include_once(ROOT."classes/models/MOrders.php");
include_once(ROOT."classes/models/MACLGroupUsers.php");
include_once(ROOT."classes/models/MOnline.php");

/**
 * Instalación de control de sesión de usuario. Para integrar con otros sistemas, utilice la clase UserIntegrate.
 *
 */

/*
 * _____________________________________________
 * Clase Usuarios CMS
 * ______________________________________________
 */


class CUser {
  
  public $username;
  public $password;
  public $password2;
  public $email;
  public $permissions;
  public $sess;
  public $user;
  public $location;
  public $lang;
  
  function CUser(){
    $this->user=new MUser();
    $this->sess=new Session(SESSION_NAME);
    $this->lang=Lang::getInstance();
    
    //Integración con otros sistemas. Utilice la clase UserIntegrate.
    $integrate=new UserIntegrate();
    $integrated_user=$integrate->getUsername();
    
    $this->permissions=0;
    
    //Remember Me
    if(isset($_COOKIE['vcmsuser'])&&isset($_COOKIE['vcmspasswd'])){
    	$this->user->setUsername($_COOKIE['vcmsuser']);
    	$this->user->setPassword($_COOKIE['vcmspasswd']);
    	if($this->user->login()){
    		$this->sess->set("username",$_COOKIE['vcmsuser']);
    	}
    }
    
    if($this->sess->exists("username") || !empty($integrated_user)){
    	if($this->sess->exists("username")){
    		$this->username=$this->sess->get("username");
    	}else{
    		$this->username=$integrated_user;
    	}
    	$this->user->setUsername($this->username);
    	$this->user->load();
    	$info=$this->user->next();
    	if($info['banned']!=1){
	    	$this->email=$info['email'];
	    	$this->permissions=$info['perms'];
	    	
	    	$s=Settings::getInstance();
	    	$settings=$s->getSettings();
	    	$online=new MOnline();
	    	$online->setTt(time()-$settings['track_online']);
	    	$online->delete();
	    	$online=new MOnline();
		  	$online->setUsername($this->username);
		  	$online->load();
		  	$online->setTt(time());
		  	if($online->getSize()==0){
		  		$online->add();
		  	}else{
		  		$online->addCriterion('username',$this->username);
		  		$online->setUsername(null);
			  	$online->update();
		  	}
	    	
	    	$models=ModelPool::getInstance();
	    	$orders=$models->getModel('MOrders');
	    	$orders->setUsername($this->username);
	    	$orders->setDistinct(true);
	    	$orders->setStatus(1);
	    	$orders->load();
	    	while($o=$orders->next()){
	    		if($o['expires']<time()){
	    			$acl=$models->getModel('MACLGroupUsers');
	    			$acl->setUsername($this->username);
	    			$acl->setAcl_groups_id($o['acl_groups_id']);
	    			$acl->delete();
	    		}
	    	}
    	}else{
    		$this->username="";
    	}
    }else {
    	$this->username="";
    }
    
    
  }
  
  function &getInstance(){
  	static $me;
  	if(!$me) {
  		$me=array(new CUser());
  	}
  	return $me[0];
  }
  
}