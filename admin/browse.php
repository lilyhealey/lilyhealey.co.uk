<?php require_once("inc/head.php"); 

//  get children (if any)
$ob = new Objects;

$fields = array("objects.name1", "objects.id AS objectsId");
$tables = array("objects", "wires");
$where = array(	"wires.fromid = '". $objects[$o]."'",
				"wires.toid = objects.id",
				"wires.active = '1'",
				"objects.active = '1'");
$order = array(	"objects.rank", 
				"name1");

$items = $ob->get_all($fields, $tables, $where, $order);
$padout = floor(log10(count($items))) + 1;
if ($padout < 2) 
	$padout = 2;
$i = 0;
$ca = array();

foreach($items as $item)
{
	// object name
	$name = $item["name1"];
	if (strlen($name) > 60)
		$name = substr($name, 0, 60) ."...";
	$ca[$i]["name"] = strip_tags($name);
	
	// object url
	$ca[$i]["url"] = $admin_path . "browse.php" . urlData();
	if (sizeof($objects))
		$ca[$i]["url"] .= ",";
	$ca[$i]["url"] .= $item["objectsId"];
	
	// object 0-padded index
	$ca[$i]["n"] = STR_PAD($i+1, $padout, "0", STR_PAD_LEFT);
	$i++;
}

if($objects[$o])
	$item = $ob->get($objects[$o]);
else
	$item = $ob->get(0);
$name = strip_tags($item["name1"]);
?>
<div id="body-container">
	<div id="body">
		<div class="parent-container"><?php
			for($i = 0; $i < count($refs); $i++)
				echo $refs[$i];
		?></div>
		<div class="self-container">
			<div class="self"><?php 
				if($object) { ?>
				<span><?php echo $name; ?></span>
				<span>
					<a href="edit.php<?php echo urlData(); ?>">edit</a>
				</span>
				<span>
					<a href="delete.php<?php echo urlData(); ?>">delete</a>
				</span><?php } 
			?></div>
		</div>
		<div class="children-container"><?php
			for($i = 0; $i < count($ca); $i++)
			{
			?><div class="child">
				<span><? echo $ca[$i]["n"];?></span>
				<span>
					<a href="<? echo $ca[$i]['url'];?>"><?
						echo $ca[$i]["name"];
					?></a>
				</span>
			</div><?php
			}
		?></div>
		<div class="actions">
			<a href="add.php<?php echo urlData(); ?>">add object</a>
			<a href="link.php<?php echo urlData(); ?>">link</a>
		</div>
	</div>
</div>
<?php require_once("inc/foot.php"); ?>
