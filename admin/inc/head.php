<?php 
// require_once("../models/Model.php");
require_once("org.php");
require_once("request.php");
require_once("config.php");

$ob = new Objects();
$mm = new Media();
$ww = new Wires();
$r = new Request();
$db = db_connect($r->remote_user);

// Check that selected object exists
if ($r->o && is_numeric($r->o))
{
	$item = $ob->get($r->o);
	
	if (!$ob->exists($r->o)) 
	{
		$url = "";
		for ($i = 0; $i < sizeof($r->objects)-1; $i++) 
		{
			if($i == 0)
				$url .= "?object=" . $r->objects[$i];
			if($i < sizeof($r->objects)-2) 
				$url .= ",";
		}
		header("location:". $admin_path ."browse.php". $url);
	}
	$name = $item["name1"];
	$title = $name;
}

// parents
$parents = $ob->parents($r->objects);

	
// self
if($r->o)
	$item = $ob->get($r->o);
else
	$item = $ob->get(0);
$name = strip_tags($item["name1"]);

// document title
$item = $ob->get($r->o);
$title = $item["name1"];
if ($title)
	$title = $db_name ." | ". $title;
else
	$title = $db_name;
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8">
		<meta http-equiv="Title" content="<?php echo $documentTitle; ?>">
		<meta name="description" content="Open Records Generator 2.0">
		<link rel="shortcut icon" href="<? echo $admin_path;?>media/icon.png">
		<link rel="apple-touch-icon-precomposed" href="<? echo $admin_path;?>media/icon.png">
		<link rel="stylesheet" href="<? echo $admin_path; ?>static/global.css">
	</head>
	<body>
		<div id="page">
			<div id="header-container">
				<div id="header">
					<a href="<?php echo $admin_path; ?>browse.php"><?php 
					echo $db_name ?> db</a>
				</div>
			</div>