<?
// path to config file
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/admin/config/config.php";
require_once($config);

// specific to this 'app'
$config_dir = $root."config/";
require_once($config_dir."url.php");
require_once($config_dir."request.php");

$db = db_connect("guest");

$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();

$title = "leh";
if($uu->id)
	$title = $oo->get($uu->id)['name1']." - ".$title;
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><? echo $title; ?></title>
		
		<!-- shortcut icon -->
        <!-- link rel="shortcut icon" href="http://24.media.tumblr.com/avatar_cb6c249c5e5c_128.png" -->
		<link rel="shortcut icon" href="/media/png/icon.png">
		<link rel="mask-icon" href="/media/svg/icon.svg">
		
		<!-- add jquery library -->
        <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
        
		<!-- my stuff -->
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/global.css">
		<script type="text/javascript" src="/static/js/global.js"></script>
		
		<!-- fancebox -->
		<link rel="stylesheet" href="/static/fancybox/source/jquery.fancybox.css" type="text/css" media="screen">
		<script type="text/javascript" src="/static/fancybox/source/jquery.fancybox.js"></script>
	</head>
	<body class="light">
		<div id="container">
            <header>
                <p><a href="/">lily healey</a></p>
                <p>young artist</p>
                <p>new york, ny</p>
                <p><a href="/contact">contact</a></p>
            </header>