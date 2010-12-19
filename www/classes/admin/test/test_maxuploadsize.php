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
			<h3>Prueba de Carga de archivos</h3>
			<p>
			  Sus l&iacute;mites de subida seg&uacute;n la configuraci&oacute;n de PHP es de '.$this->_encode($limit).'. Por favor, introduzca un l&iacute;mite inferior o aumente los siguientes valores en php.ini:
			  <ul>
			    <li>post_max_size (current: '.ini_get('post_max_size').')</li>
			    <li>upload_max_filesize (current: '.ini_get('upload_max_filesize').')</li>
			  </ul>
			</p>';
			
		}elseif(!is_writable(ROOT.FILES)||!is_writable(ROOT.THUMBNAILS)){
			return '<h3>Prueba de Carga de archivos</h3><p>Los archivos y/o miniaturas directorios no son modificables, por favor, compruebe los permisos</p>';
		}else{
			return '<h3>Prueba de Carga de archivos</h3><p>Su configuraci&oacute;n de carga de archivos funciona correctamente.</p>';
		}
	}
	
	function _encode($value){
		return ($value/1024/1024).'M';
	}
}
?>