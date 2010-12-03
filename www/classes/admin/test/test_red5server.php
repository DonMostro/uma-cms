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
			return '<h3>Red5 servidor de prueba</h3><p>Red5 detectado.</p>';
		}else{
			return '<h3>Red5 servidor de prueba</h3><p>Red5 servidor no responde, por favor, compruebe la dirección URL del servidor y la configuración de Red5.</p>';
		}
	}
}
?>