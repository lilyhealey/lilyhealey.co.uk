<?php
require_once("inc/head.php"); 

$uri = explode('/', $_SERVER['REQUEST_URI']);
$page = $uri[2];
$pfile = $page.".php";

$o_slugs = array_slice($uri, 3);

if($page) {
	try {
		if(!file_exists($pfile))
			throw new Exception("404");
		else
			require_once($pfile);
	}
	catch(Exception $e) {
		// change to 404 error
		require_once("cover.php");
	}
}
else
	require_once("cover.php");

require_once("inc/foot.php"); 
?>