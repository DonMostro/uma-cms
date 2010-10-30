<?
ini_set("display_errors","On");
error_reporting(E_ALL);
include_once("root.php");
include_once(ROOT."classes/lib/ModelPool.php");
$MPool = new ModelPool;
print_r(class_implements(new ModelPool()));
print_r(class_parents(new ModelPool()));
$model = $MPool->getModel("MVideos");
/*var_dump($MPool);
var_dump($model);*/
?>