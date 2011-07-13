<?php
/**
 * Configuraci&oacute;n Est&aacute;tica
 */
date_default_timezone_set('UTC'); 
define('DB_SERVER','localhost');
define('DB_USER','ztvuser');
define('DB_PASSWORD','ztvpassword');
define('DB_DATABASE','ztv_cms');

define('ADMIN_USERNAME','admin');
define('ADMIN_PASSWORD','admin');


define('URL','http://uma-cms.local');
define('SITENAME','Zoila TV');
define('EMAIL','rodrigo.riquelme.e@gmail.com');
define('MOD_REWRITE',false);
define('FILES','files');
define('FILES_IMAGES','files/images');
define('TABLE_PREFIX','ztv_');
define('FILES_THUMBNAILS','files/thumbnails');
define('CACHE','cache');
define('SESSION_NAME','cms');
include_once("root.php");
include(ROOT."map.php");
?>
