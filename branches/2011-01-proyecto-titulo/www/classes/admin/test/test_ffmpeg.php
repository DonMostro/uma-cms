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
				return '<h3>Prueba de FFmpeg</h3><p>FFmpeg detectado y funcionando correctamente.</p>';
			}else{
				return '
				<h3>Prueba de FFmpeg</h3>
				<p>FFmpeg no puede ser ejecutado. Por favor, compruebe los siguientes ajustes		:
				  <ul>
				    <li>Aseg&uacute;rese de que ha ingresado la ruta correcta</li>
				  	<li>Aseg&uacute;rese de que <i>safe_mode</i> est&aacute; desactivado en php.ini</li>
				    <li>Aseg&uacute;rese de que<i>exec</i> no aparece en <i>disable_functions</i> en php.ini</li>
				    <li>Aseg&uacute;rese de que tiene permisos de escritura en el directorio de archivos		    
				  </ul>
				</p>';
			}
			return '';
	}
}
?>