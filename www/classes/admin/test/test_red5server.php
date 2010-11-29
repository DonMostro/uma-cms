<?php
include_once('root.php');
include_once(ROOT.'config.php');
include_once(ROOT.'classes/admin/test/test.php');
include_once(ROOT.'classes/lib/Types.php');

class TestRed5Server extends Test{
	
	function TestRed5Server($value=''){
		parent::Test($value);
	}
	
	function run(){
		$url=parse_url($this->value);
		if(@file_get_contents('http://'.$url['host'].':5080'.$url['path'])){
			return '<h3>Red5 Server Test</h3><p>Red5 detected.</p>';
		}else{
			return '<h3>Red5 Server Test</h3><p>Red5 server is not responding, please check the server URL and Red5 configuration.</p>';
		}
	}
}
?>