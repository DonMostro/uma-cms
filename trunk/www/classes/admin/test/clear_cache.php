<?php
include_once('root.php');
include_once(ROOT.'config.php');
include_once(ROOT.'classes/admin/test/test.php');
include_once(ROOT.'classes/models/MCache.php');

class ClearCache extends Test{
	
	function ClearCache($value=''){
		parent::Test($value);
	}
	
	function run(){
		$cache = new MCache();
		$cache->delete();
		return 'Cache cleared.';
	}
	
	function display(){
		return '<input value="Limpiar Cach&eacute;" type="button" onclick="popup(\'index.php?p=settings&f='.urlencode(get_class($this)).'&v=1&ajax\')"';
	}
}
?>