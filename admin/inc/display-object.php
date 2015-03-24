<?php
// parents
$refs[] = "";
for ($o = 0; $o < count($objects) - 1; $o++) 
{
	$item = $ob->get($objects[$o]);
	$name = strip_tags($item["name1"]);

	// Each panel expands on title click
	$refs[$o] = "<div class='parent'>";
	$refs[$o] .= "<a href='". $admin_path ."browse.php?object=";
	for ($i = 0; $i < $o + 1; $i++)
	{
		$refs[$o] .= $objects[$i];
		if ($i < $o)
			$refs[$o] .= ",";
	}
	$refs[$o] .= "'>". $name ."</a></div>";
}

// self
if($objects[$o])
	$item = $ob->get($objects[$o]);
else
	$item = $ob->get(0);
$name = strip_tags($item["name1"]);

if ($object && ($pageName != "browse") && ($pageName != "edit"))
{
	$refs[] = "<a href='browse.php". urlData() ."'>";
	$refs[] .= $name;
	$refs[] .= "</a>";
}
?>