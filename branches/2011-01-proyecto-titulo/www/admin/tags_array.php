<?php
require_once("root.php");
require_once(ROOT."config.php");
require_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/models/MTags.php");

DAO::connect();

$items = array();
$sql = "SELECT DISTINCT(tag) FROM ztv_tags ORDER BY tag ";
$qry = mysql_query($sql);
while($row = mysql_fetch_assoc($qry)){
	$tag = html_entity_decode($row['tag'],ENT_QUOTES,'utf-8');
	$items[$tag] = $tag;
}
@mysql_close();

$q = strtolower($_GET["q"]);
if (!$q) return;

foreach ($items as $key=>$value) {
//	if (strpos(strtolower($key), $q) !== false) {
	if (strpos(strtolower($key), $q) === 0) {
		echo "$key|$value\n";
	}
}
?>