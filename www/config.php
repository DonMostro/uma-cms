<?php
//Configuración
date_default_timezone_set('UTC'); 
define('DB_SERVER','localhost');
define('DB_USER','ztvuser');
define('DB_PASSWORD','ztvpassword');
define('DB_DATABASE','ztv_cms');

define('ADMIN_USERNAME','admin');
define('ADMIN_PASSWORD','admin');


define('URL','http://dev-ztv-com.lab.proyectouvm.com');
define('URL_STATIC','http://dev-ztv-com.lab.proyectouvm.com');
define('SITENAME','Zoila TV');
define('EMAIL','rodrigo.riquelme.e@gmail.com');
define('MOD_REWRITE',false);
define('FILES','files');
define('SMALL_VIDEOS', 'small_videos');
define('FILES_TOPVIDEOS','topvideos');
define('TABLE_PREFIX','ztv_');
//define('FILES_XML_VIDEOS','xml_videos');
define('IMG_FILES_TOPVIDEOS','topvideos/images');
define('IMG_FILES_ORIG_TOPVIDEOS','topvideos/images_orig');
define('THUMBNAILS','thumbnails');
define('CACHE','cache');
define('SESSION_NAME','cms');
//include_once("config.canales.php");
include_once("root.php");
include(ROOT."map.php");
?>
