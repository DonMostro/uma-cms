<?php
include_once('root.php');
include_once(ROOT.'config.php');
include_once(ROOT.'classes/admin/test/test.php');

class TestFFmpeg extends Test{
	
	function TestFFmpeg($value=''){
		parent::Test($value);
	}
	
	function run(){
		$path=ROOT.FILES;
		$command=$this->value." -y -i $path/test.avi -s 320x240 -ar 44100 $path/test.flv";
		exec($command);
		if(file_exists($path.'/test.flv')&&filesize($path.'/test.flv')){
				unlink($path.'/test.flv');
				return '<h3>Testing FFmpeg</h3><p>FFmpeg is detected and working properly.</p>';
			}else{
				return '
				<h3>Testing FFmpeg</h3>
				<p>FFmpeg cannot be executed. Please check the following settings:
				  <ul>
				    <li>Make sure you have entered the correct path to ffmpeg</li>
				  	<li>Make sure the <i>safe_mode</i> setting is turned off in php.ini</li>
				    <li>Make sure that <i>exec</i> function is not listed under <i>disable_functions</i> setting in php.ini</li>
				    <li>Make sure there are writable permission set to the files directory
				  </ul>
				</p>';
			}
			return '';
	}
}
?>