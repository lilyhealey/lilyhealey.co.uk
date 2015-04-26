<?php
require_once("config.php");
require_once("model.php");
require_once("objects.php");
require_once("wires.php");
require_once("media.php");
require_once("url.php");
require_once("request.php");

// logged in user via .htaccess, .htpasswd
$user = $_SERVER['REDIRECT_REMOTE_USER'];
$db = db_connect($user);

$ob = new Objects();
$mm = new Media();
$ww = new Wires();
$u = new URL();
$r = new Request();

// Check that selected object exists
if ($u->id && is_numeric($u->id))
{
	$item = $ob->get($u->id);
	
	if (!$ob->active($u->id)) 
	{
		$url = "";
		for ($i = 0; $i < sizeof($u->ids)-1; $i++) 
		{
			if($i == 0)
				$url .= "?object=" . $u->ids[$i];
			if($i < sizeof($u->ids)-2) 
				$url .= ",";
		}
		header("location:". $admin_path ."browse.php". $url);
	}
	$name = $item["name1"];
	$title = $name;
}

// parents
$parents = $ob->parents($u->ids);

// $u = $ob->objects_to_url($r->objects);
// print_r($u);
// self
if($u->id)
	$item = $ob->get($u->id);
else
	$item = $ob->get(0);
$name = strip_tags($item["name1"]);

// document title
$item = $ob->get($u->id);
$title = $item["name1"];
if ($title)
	$title = $db_name ." | ". $title;
else
	$title = $db_name;
	
function slug($name = "untitled")
{
	$pattern = '/(\A\W+|\W+\z)/';
	$replace = '';
	$tmp = preg_replace($pattern, $replace, $name);
	
	$pattern = '/\s+/';
	$replace = '-';
	$tmp = preg_replace($pattern, $replace, $tmp);
	
	$pattern = '/[^-\w]+/';
	$replace = '';
	$tmp = preg_replace($pattern, $replace, $tmp);
	return strtolower($tmp);
}
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
				<div id="header" class="centre">
					<a href="<?php echo $admin_path; ?>browse"><?php 
						echo $db_name ?> db</a>
				</div>
			</div>