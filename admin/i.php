<?php
require_once("inc/head.php");

$r = explode('/', $_SERVER['REQUEST_URI']);

try
{
	if($r[2] == "add")
		require_once("add.php");
	if($r[2] == "browse")
		require_once("browse.php");
	if($r[2] == "edit")
		require_once("edit.php");
	if($r[2] == "info")
		require_once("info.php");
	if($r[2] == "link")
		require_once("link.php");
	if(count($r) < 3)
		require_once("index.php");
}
catch(Exception $e)
{
	
}

print_r($r);

require_once("inc/foot.php");
?>