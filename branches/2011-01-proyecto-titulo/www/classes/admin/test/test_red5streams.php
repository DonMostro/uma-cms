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
			return '<h3>Red5 flujos de prueba</h3><p>El Red5 flujos de la guía se encuentra.</p>';
		}else{
			return '
			<h3>Red5 flujos de prueba</h3>
			<p>El camino que has introducido no es legible. Por favor, compruebe lo siguiente:
			  <ul>
			    <li>Compruebe si la ruta que ha introducido es correcta</li>
			    <li>Verifica en el<i>open_basedir</i> setting in php.ini, si no está vacío, escriba la ruta de Red5 flujos</li>
			    <li>Asegúrese de que<i>safe_mode</i> configuración está desactivada en php.ini</li>
			  </ul>
			</p>';
		}
	}
}
?>