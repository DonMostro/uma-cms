<?php
ini_set("display_errors","on");
error_reporting(E_ALL);

include_once(ROOT."config.php");
include_once(ROOT."map.php");
include_once(ROOT."version.php");
include_once(ROOT."cache.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MSettings.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/IModel.php");
include_once(ROOT."classes/lib/Observable.php");
include_once(ROOT."classes/lib/QueryBuilder.php");
include_once(ROOT."classes/models/MEmoticons.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Session.php");
include_once(ROOT."classes/models/MCache.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/models/MThumbnails.php");
include_once(ROOT."classes/models/MImages.php");
include_once(ROOT."classes/models/MTags.php");
include_once(ROOT."classes/models/MBufferedModel.php");
include_once(ROOT."classes/models/MWatched.php");


/**
   * Clase controladora principal
   * */
class Dispatcher {
	private $map = array();
	private $noncached = array();
	private $controllers = array();
	
	/**
	 * Constructor
	 * @param $map
	 * @param $noncached
	 */
	
	public function Dispatcher($map, $noncached){
		$this->map = $map;
		$this->noncached = $noncached;
	}
	
	/**
	 * Ejecuta el controlador seleccionado y muestra la salida HTML
	 */
	
	public function run(){
		//var_dump($this->map);
		$model = @$_REQUEST['m'];
		$Command = $this->map[$model];
		include_once(ROOT."classes/controllers/$Command.php");

		if(class_exists($Command)){
			$command = new $Command;
		}else{
			//throw new Exception("No se ha encontrado una clase con el nombre $command");
			include_once(ROOT."classes/controllers/CHome.php");
			$command = new CHome();
		}

		
		$command->run();
		echo($command->show());

	}
}
?>