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
			<h3>Archivo de prueba carga</h3>
			<p>
			  ajustes de configuración de PHP límite de tamaño de archivo subido a '.$this->_encode($limit).'. Por favor, introduzca un límite inferior o aumentar los siguientes ajustes en php.ini:
			  <ul>
			    <li>post_max_size (current: '.ini_get('post_max_size').')</li>
			    <li>upload_max_filesize (current: '.ini_get('upload_max_filesize').')</li>
			  </ul>
			</p>';
			
		}elseif(!is_writable(ROOT.FILES)||!is_writable(ROOT.THUMBNAILS)){
			return '<h3>Archivo de prueba carga</h3><p>Los archivos y / o miniaturas directorios no son modificables, por favor, compruebe los permisos</p>';
		}else{
			return '<h3>Archivo de prueba carga</h3><p>La configuración de carga de archivos están bien.</p>';
		}
	}
	
	function _encode($value){
		return ($value/1024/1024).'M';
	}
}
?>