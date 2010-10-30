<?
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."map.php");
include_once(ROOT."version.php");
include_once(ROOT."classes/lib/Observable.php");
include_once(ROOT."classes/lib/Mailer.php");
include_once(ROOT."classes/lib/Observer.php");
include_once(ROOT."classes/lib/Lang.php");
include_once(ROOT."classes/models/MText.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MSettings.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/IModel.php");
include_once(ROOT."classes/lib/QueryBuilder.php");
include_once(ROOT."classes/models/MEmoticons.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/models/MUser.php");
include_once(ROOT."classes/models/MFavorites.php");
include_once(ROOT."classes/models/MComments.php");
include_once(ROOT."classes/models/MChannels.php");
include_once(ROOT."classes/models/MChannelElements.php");
include_once(ROOT."classes/views/VNotification.php");
include_once(ROOT."classes/lib/Template.php");
include_once(ROOT."classes/views/VView.php");
include_once(ROOT."classes/models/MAds.php");
include_once(ROOT."classes/lib/CacheController.php");
include_once(ROOT."cache.php");
include_once(ROOT."classes/models/MCache.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/models/MThumbnails.php");
include_once(ROOT."classes/models/MImages.php");
include_once(ROOT."classes/models/MTags.php");
include_once(ROOT."classes/models/MBufferedModel.php");
include_once(ROOT."classes/models/MWatched.php");

class ModelPool{
    private $observers = array();

    public function &getInstance(){
          static $me;
          if(!$me) {
              $me=array(new ModelPool());
          }
          return $me[0];
      }

    public function getModel($model){
        if(class_exists($model)) $instance = new $model;
        else throw new Exception("No se ha encontrado una clase con el
nombre $model");
        return $instance;        
    }
}
?>