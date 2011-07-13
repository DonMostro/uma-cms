<?
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."map.php");
include_once(ROOT."version.php");
include_once(ROOT."classes/lib/Observable.php");
include_once(ROOT."classes/lib/Observer.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MSettings.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/IModel.php");
include_once(ROOT."classes/lib/QueryBuilder.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/lib/CacheController.php");
include_once(ROOT."cache.php");
include_once(ROOT."classes/models/MCache.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/models/MThumbnails.php");
include_once(ROOT."classes/models/MTags.php");
include_once(ROOT."classes/models/MBufferedModel.php");

class ModelPool{
	static $me;
    private $observers = array();

    public function getInstance(){
	    if(!$this->me) {
    		$this->me=array(new ModelPool());
    	}
        return $this->me[0];
    }

    public function getModel($model){
        if(class_exists($model)) $instance = new $model;
        else throw new Exception("No se ha encontrado una clase con el
nombre $model");
        return $instance;        
    }
}
?>
