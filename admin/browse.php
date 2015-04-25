<?php require_once("inc/head.php"); 

//  get children (if any)
$children = $ob->children($r->o);

$pad = floor(log10(count($children))) + 1;
if($pad < 2) 
	$pad = 2;

for($i = 0; $i < count($children); $i++)
{
	// truncate long names
	$name = $children[$i]["name1"];
	if (strlen($name) > 60)
		$name = substr($name, 0, 60) ."...";
	$children[$i]["name"] = strip_tags($name);
	
	// object url
	$children[$i]["url"] = $admin_path . "browse.php" . $r->url_data();
	if (sizeof($r->objects))
		$children[$i]["url"] .= ",";
	$children[$i]["url"] .= $children[$i]["o"];
	
	// object 0-padded index
	$children[$i]["n"] = str_pad($i+1, $pad, "0", STR_PAD_LEFT);
}

if($r->o)
	$item = $ob->get($r->o);
else
	$item = $ob->get(0);
$name = strip_tags($item["name1"]);

?>
<div id="body-container">
	<div id="body" class="centre">
		<div class="parent-container"><?php 
			for($i = 0; $i < count($parents); $i++) 
			{ 
			?><div class="parent">
				<a href="<?php echo $parents[$i]['url']; ?>"><? 
					echo $parents[$i]['name'];
				?></a>
			</div><?php 
			} 
		?></div>
		<div class="self-container">
			<div class="self"><?php 
				if($r->o) { ?>
				<span><?php echo $name; ?></span>
				<span>
					<a href="edit.php<?php echo $r->url_data(); ?>">edit</a>
				</span>
				<span>
					<a href="delete.php<?php echo $r->url_data(); ?>">delete</a>
				</span><?php } 
			?></div>
		</div>
		<div class="children-container"><?php
			for($i = 0; $i < count($children); $i++)
			{
			?><div class="child">
				<span><? echo $children[$i]["n"]; ?></span>
				<span>
					<a href="<? echo $children[$i]['url'];?>"><?
						echo $children[$i]["name"];
					?></a>
				</span>
			</div><?php
			}
		?></div>
		<div class="actions">
			<a href="add.php<?php echo $r->url_data(); ?>">add object</a>
			<a href="link.php<?php echo $r->url_data(); ?>">link</a>
		</div>
	</div>
</div>
<?php require_once("inc/foot.php"); ?>
