<?php
include_once('root.php');
include_once(ROOT.'config.php');
include_once(ROOT.'cache.php');
include_once(ROOT.'classes/lib/Observer.php');
include_once(ROOT."classes/models/MCache.php");

class CacheController extends Observer {
	
	public function __construct(&$o){
		parent::Observer($o);
	}
	
	public function update(){
		switch($this->subject->getState()){
			case 'change_immediate':
				$this->_removeFromCache();
				break;
			case 'change_delayed':
				break;
			default:;
		}
		
    }
    
    private function _removeFromCache(){
    	$model = get_class($this->subject);
    	if(isset(CacheConfig::$config[$model])){
	    	$cache = new MCache();
	    	$cache->setCommand(CacheConfig::$config[$model]);
	    	$cache->delete();
    	}
    }
	
}
?>