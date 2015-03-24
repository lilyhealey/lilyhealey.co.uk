<?php 
require_once("org.php");
require_once("config.php");
require_once("variables.php");
require_once("display-object.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $documentTitle; ?></title>
		<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8">
		<meta http-equiv="Title" content="<?php echo $documentTitle; ?>">
		<meta name="description" content="Open Records Generator 2.0">
		<link rel="shortcut icon" href="<? echo $dbAdmin;?>media/icon.png">
		<link rel="apple-touch-icon-precomposed" href="<? echo $dbAdmin;?>media/icon.png">
		<link rel="stylesheet" href="<?php echo $dbAdmin; ?>static/global.css">
	</head>
	<body>
		<div id="page">
			<div id="header-container">
				<div id="header">
					<a href="<?php echo $dbAdmin; ?>browse.php"><?php 
					echo $dbClient; ?> db</a>
				</div>
			</div>