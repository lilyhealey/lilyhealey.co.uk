<?php
$pageName = basename($_SERVER['PHP_SELF'], ".php");
$remote_user = $_SERVER['REDIRECT_REMOTE_USER'];
$object = $_REQUEST['object'];
// $id = $_REQUEST['id']; // i don't think this variable is being used?

// add.php, edit.php
$action = $_REQUEST['action'];
$submit = $_REQUEST['submit'];

$name1 = $_REQUEST['name1'];
$deck = $_REQUEST['deck'];
$body = $_REQUEST['body'];
$notes = $_REQUEST['notes'];
$begin = $_REQUEST['begin'];
$end = $_REQUEST['end'];
$url = $_REQUEST['url'];
$rank = $_REQUEST['rank'];
$uploadsMax = $_REQUEST['uploadsMax'];

$mediaCaption = $_REQUEST['mediaCaption'];	
$mediaId = $_REQUEST['mediaId'];	
$mediaRank = $_REQUEST['mediaRank'];
$mediaDelete = $_REQUEST['mediaDelete'];
$mediaType = $_REQUEST['mediaType'];

// link.php
$wirestoid = $_REQUEST['wirestoid'];

// database settings
$dbClient = "leh";
$dbHost = "http://lilyhealey.co.uk/";
$host = "http://lilyhealey.co.uk/";
$dbAdmin = $dbHost ."admin/";
$admin_path = $host ."admin/";
$dbMedia = $dbHost ."media/"; // don't forget to set permissions on this folder
$media_path = $host ."media/"; // don't forget to set permissions on this folder

$ob = new Objects;

// Debug           
// print_r($_REQUEST);
// print_r($_SERVER);

/* 	
	login via .htaccess and .htpasswd 
	connect to database as authenticated user 
*/
$db = db_connect($remote_user);

// Handle multiple objects if any
$objects = explode(",", $object);

// $object refers to most recent of the $objects
$object = $objects[sizeof($objects) - 1];
if (!$object)
	$object = 0;

// Check that selected object exists
if ($object && is_numeric($object))
{
	$sql = "SELECT 
				id, 
				active, 
				name1, 
				name2 
			FROM objects 
			WHERE 
				id = '$object' 
				AND active = '1' 
			LIMIT 1";
	$result = MYSQL_QUERY($sql);
	$myrow = MYSQL_FETCH_ARRAY($result);
	
	if (!$myrow["id"]) 
	{
		$urlTemp = "?object=";
		for ($i = 0; $i < sizeof($objects)-1; $i++) 
		{
			$urlTemp .= $objects[$i];
			if ($i < sizeof($objects)-2) $urlTemp .= ",";
		}
		header("location:". $dbAdmin ."browse.php". $urlTemp);
	}
	$name = $myrow["name1"];
	if ($myrow["name2"]) 
		$name .= " ". $myrow["name2"];
	$documentTitle = $name;
}

// Clean up $objects because explode() gives it an array size even if null!
if (sizeof($objects) == 1 && empty($objects[sizeof($objects) - 1])) 
	unset($objects);


// build urls
function urlData() 
{
	global $objects;
	$url = "?object=";
	for ($i = 0; $i < sizeof($objects); $i++) 
	{
		$url .= $objects[$i];
		if ($i < sizeof($objects) - 1) 
			$url .= ",";
	}
	return $url;
}

function urlBack() 
{
	global $objects;
	$url = "?object=";
	for ($i = 0; $i < sizeof($objects) - 1; $i++) 
	{
		$url .= $objects[$i];
		if ($i < sizeof($objects) - 2) 
			$url .= ",";
	}
	return $url;
}

// document header
if ($documentTitle)
	$documentTitle = $dbClient ." | ". $documentTitle;
else
	$documentTitle = $dbClient;
?>