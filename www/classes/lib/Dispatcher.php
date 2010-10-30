<?php
ini_set("display_errors","on");
error_reporting(E_ALL);

include_once(ROOT."config.php");
include_once(ROOT."map.php");

include_once(ROOT."cache.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Session.php");
include_once(ROOT."classes/lib/Observable.php");
include_once(ROOT."classes/lib/QueryBuilder.php");

include_once(ROOT."classes/models/MText.php");
include_once(ROOT."classes/models/MSettings.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/IModel.php");
include_once(ROOT."classes/models/MEmoticons.php");
include_once(ROOT."classes/models/MCache.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/models/MThumbnails.php");
include_once(ROOT."classes/models/MImages.php");
include_once(ROOT."classes/models/MTags.php");
include_once(ROOT."classes/models/MBufferedModel.php");
include_once(ROOT."classes/models/MWatched.php");



class Dispatcher{
	private $map = array();
	private $noncached = array();
	private $controllers = array();
	private $http=null;
	
	public function Dispatcher($map, $noncached){
		$this->map = $map;
		$this->noncached = $noncached;
	}
	
	public function run(){
		//var_dump($this->map);
		$model = @$_REQUEST['m'];
		$Controller = $this->map[$model];
		echo $Controller."kk";
		include_once(ROOT."classes/controllers/$Controller.php");

		if(class_exists($Controller)){
			$controller = new $Controller;
		}else{
			//throw new Exception("No se ha encontrado una clase con el nombre $command");
			include_once(ROOT."classes/controllers/CHome.php");
			$controller = new CHome();
		}


		
		$controller->run();
		print(trim($controller->show()));

	}
	
	public function addController($classController){
		array_push($controllers, new $classController());
	}
	
	public function displayError($string){
		echo $string;
	}
	
	private function _findfile(){
		
	}
	
	private function _checkIP(){
		
	}
	
}
?>