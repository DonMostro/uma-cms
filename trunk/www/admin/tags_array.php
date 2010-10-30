<?php

ini_set("display_errors","On");
error_reporting(E_ALL);
require_once("root.php");
include_once(ROOT."classes/models/MTags.php");

mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
mysql_select_db(DB_DATABASE);
$items = array();
$sql = "SELECT DISTINCT(tag) FROM tags ORDER BY tag ";
$qry = mysql_query($sql);
while($row = mysql_fetch_assoc($qry)){
	$tag = html_entity_decode($row['tag'],ENT_QUOTES,'utf-8');
	$items[$tag] = $tag;
}
mysql_close();

$q = strtolower($_GET["q"]);
if (!$q) return;

foreach ($items as $key=>$value) {
//	if (strpos(strtolower($key), $q) !== false) {
	if (strpos(strtolower($key), $q) === 0) {
		echo "$key|$value\n";
	}
}
?>