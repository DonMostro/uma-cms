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
				return '<h3>Testing FFmpeg</h3><p>FFmpeg se detecta y funciona correctamente.</p>';
			}else{
				return '
				<h3>Testing FFmpeg</h3>
				<p>FFmpeg no puede ser ejecutado. Por favor, compruebe los siguientes ajustes		:
				  <ul>
				    <li>Aseg&uacute;rese de que ha entrado en el camino correcto ffmpeg</li>
				  	<li>Aseg&uacute;rese de que el<i>safe_mode</i> configuraci�n se desactiva en php.ini</li>
				    <li>Aseg&uacute;rese de que<i>exec</i> la funci�n no aparece en <i>disable_functions</i> puesta en php.ini</li>
				    <li>Aseg&uacute;rese de que el permiso de escritura establece en el directorio de archivos		    
				  </ul>
				</p>';
			}
			return '';
	}
}
?>