<?php
include_once('root.php');
include_once(ROOT.'config.php');
include_once(ROOT.'classes/admin/test/test.php');
include_once(ROOT.'classes/lib/Types.php');

class TestRed5Streams extends Test{
	
	function TestRed5Streams($value=''){
		parent::Test($value);
	}
	
	function run(){
		if(is_readable($this->value)){
			return '<h3>Red5 Streams Test</h3><p>The Red5 streams directory is found.</p>';
		}else{
			return '
			<h3>Red5 Streams Test</h3>
			<p>The path you have entered is not readable. Please check the following:
			  <ul>
			    <li>Check if the path you have entered is correct</li>
			    <li>Check the <i>open_basedir</i> setting in php.ini, if it is not empty, enter the path to Red5 streams</li>
			    <li>Make sure <i>safe_mode</i> setting is off in php.ini</li>
			  </ul>
			</p>';
		}
	}
}
?>