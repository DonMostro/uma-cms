<?php
include_once('root.php');
include_once(ROOT.'config.php');
include_once(ROOT.'classes/admin/test/test.php');
include_once(ROOT.'classes/lib/Types.php');

class TestMaxUploadSize extends Test{
	
	function TestMaxUploadSize($value=''){
		parent::Test($value);
	}
	
	function run(){
		$post=new FileSize(ini_get('post_max_size'));
		$upload=new FileSize(ini_get('upload_max_filesize'));
		$limit=$post->getValue()<$upload->getValue()?$post->getValue():$upload->getValue();
		$value=new FileSize($this->value);
		
		if($value->getValue() > $post->getValue() || $value->getValue() > $upload->getValue()){
			return '
			<h3>File uploading test</h3>
			<p>
			  PHP configuration settings limit uploaded file size to '.$this->_encode($limit).'. Please enter a lower limit or increase the following settings in php.ini:
			  <ul>
			    <li>post_max_size (current: '.ini_get('post_max_size').')</li>
			    <li>upload_max_filesize (current: '.ini_get('upload_max_filesize').')</li>
			  </ul>
			</p>';
			
		}elseif(!is_writable(ROOT.FILES)||!is_writable(ROOT.THUMBNAILS)){
			return '<h3>File uploading test</h3><p>The files and/or thumbnails directories are not writable, please check the permissions</p>';
		}else{
			return '<h3>File uploading test</h3><p>The file uploading settings are OK.</p>';
		}
	}
	
	function _encode($value){
		return ($value/1024/1024).'M';
	}
}
?>