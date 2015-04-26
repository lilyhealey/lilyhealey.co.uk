<?php

//  get children (if any)
$children = $ob->children($u->id);

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
	$children[$i]["url"] = $admin_path . "browse/" . $u->urls();
	if (sizeof($u->ids))
		$children[$i]["url"] .= "/";
	$children[$i]["url"] .= $children[$i]["o"];
	
	// object 0-padded index
	$children[$i]["n"] = str_pad($i+1, $pad, "0", STR_PAD_LEFT);
}

if($u->id)
	$item = $ob->get($u->id);
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
				if($u->id) { ?>
				<span><?php echo $name; ?></span>
				<span>
					<a href="<? echo $admin_path; ?>edit/<?php echo $u->urls(); ?>">edit</a>
				</span>
				<span>
					<a href="<? echo $admin_path; ?>delete/<?php echo $u->urls(); ?>">delete</a>
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
// 			print_r($u->ids);
// 			print_r($u->urls);
// 			echo $u->id;
// 			echo $u->url;
// 			print_r($children);
		?></div>
		<div class="actions">
			<a href="<? echo $admin_path; ?>add/<?php echo $u->urls(); ?>">add object</a>
			<a href="<? echo $admin_path; ?>link/<?php echo $u->urls(); ?>">link</a>
		</div>
	</div>
</div>