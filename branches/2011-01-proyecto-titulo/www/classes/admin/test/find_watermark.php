<?php
include_once('root.php');
include_once(ROOT.'config.php');
include_once(ROOT.'classes/lib/DAO.php');
include_once(ROOT.'classes/admin/test/test.php');

class FindWatermark extends Test{
	
	function FindWatermark($value=''){
		parent::Test($value);
	}
	
	function run(){

		$o=array();
		exec("find / -name 'watermark.so' -print", $o);

		echo implode('<br />',$o);
	}
	
	function display(){
		return '<input value="Find" type="button" onclick="popup(\'index.php?p=settings&f='.urlencode(get_class($this)).'&v=\'+escape(document.getElementById(\'edit'.$this->id.'_0\').value)+\'&ajax\')" />';
	}
}
?>