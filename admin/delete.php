<?php require_once("inc/head.php"); 

if (strtolower($action) != "delete") 
{
	//  Get ALL objects where "fromid" = this object about to be deleted
	$connectedAll = array();
	$i = 0;
	$sql = "SELECT 
				*, 
				objects.id AS objectId 
			FROM 
				wires, 
				objects 
			WHERE 
				wires.fromid = '$object' 
			AND wires.toid = objects.id 
			AND wires.active = 1 
			AND objects.active = 1 
			ORDER BY 
				name1, 
				name2";
	$result = MYSQL_QUERY($sql);
	while ($myrow = MYSQL_FETCH_ARRAY($result)) 
	{
		$connectedAll[$i] = array();
		$connectedAll[$i]["id"] = $myrow["objectId"];
		$connectedAll[$i]["name"] = $myrow["name1"];
		if ($myrow["name2"])
			$connectedAll[$i]["name"] .= " ". $myrow["name2"];
		if (strlen($connectedAll[$i]["name"]) > 40)
			$connectedAll[$i]["name"] = substr($connectedAll[$i]["name"], 0, 60) ."...";
		$connectedAll[$i]["dependent"] = TRUE;
		$i++;
	}

	//  Narrow down to objects dependent on this object about to be deleted
	$j = $i;
	$k = 0;
	for ($i = 0; $i < $j; $i++) 
	{
		$sql = "SELECT * 
				FROM wires 
				WHERE toid = '". $connectedAll[$i]["id"] ."' 
				ORDER BY modified DESC";
		$result = MYSQL_QUERY($sql);
		while ($myrow = MYSQL_FETCH_ARRAY($result)) 
		{
			if ($myrow["fromid"] != $object)
				$connectedAll[$i]["dependent"] = FALSE;
		}
		if ($connectedAll[$i]["dependent"] == TRUE)
			$k++;
	}
	
	//  Display warning
	?>
	<div class='monoHvy'>
		<p>WARNING!</p>
		<p>You are about to permanently delete this object.</p>
		<p>If this object is linked, the original will not be deleted.</p>
	</div>
	<?php

	$l = 0;
	if ($k) 
	{
		$numrows = $k;
		$padout = floor(log10($numrows)) + 1;
		if ($padout < 2) 
			$padout = 2;
		echo "\nThe following ". $k ." objects will also be deleted as a result: <br /><br />";
		for ($i = 0; $i < $j; $i++) 
		{
			$l++;
			if ($connectedAll[$i]["dependent"] == TRUE) 
				echo "\n";
				echo STR_PAD($l, $padout, "0", STR_PAD_LEFT);
				echo " <a href='" . $dbAdmin ."browse.php". urlData() .",". $connectedAll[$i]["id"] . "'>";
				echo strip_tags($connectedAll[$i]["name"]) ."</a><br />";
		}
	}

	?>
	<form action="<?php echo $PHP_SELF . urlData(); ?>" method="post">
		<input name='action' type='hidden' value='delete'>
		<input name='cancel' type='button' value='Cancel' onClick="javascript:history.back();"> 
		<input name='submit' type='submit' value='Delete Object'>
	</form>
	<?php
} 
else 
{	
	//  Get wire that goes to this object to be deleted
	if (sizeof($objects) < 2) 	
		$fromObject = 0;
	else
		$fromObject = $objects[sizeof($objects) - 2];

	$sql = "SELECT * 
			FROM wires 
			WHERE 
				toid = '$object' 
			AND fromid = '$fromObject' 
			AND active = '1' 
			LIMIT 1";
	$result = MYSQL_QUERY($sql);
	$myrow = MYSQL_FETCH_ARRAY($result);
	$thisWire = $myrow['id'];
	$sql = "UPDATE wires 
			SET active = '0' 
			WHERE id = '$thisWire'";
	$result = MYSQL_QUERY($sql);
	?>
	<div class='monoHvy'>
		Record deleted sucessfully.
	</div>
	<a href="<?php echo $dbAdmin; ?>browse.php<? echo urlBack(); ?>">continue...</a>
	<?php
}
require_once("inc/foot.php"); ?>