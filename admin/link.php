<?php require_once("inc/head.php"); ?>
<div id="body-container">
<div id="body">
	<div class="parent-container"><?php 
		for($i = 0; $i < count($parents); $i++) 
		{ 
		?><div class="parent">
			<a href="<?php echo $parents[$i]['url']; ?>"><? 
				echo $parents[$i]['name'];
			?></a>
		</div><?php 
		} 
	?></div><?php
if ($r->action != "link") 
{
?><div class="self-container">
	<div class="self">
		<a href="browse.php<?php echo $r->url_data(); ?>"><?php echo $name ?></a>
	</div>
	<div class="self">
		<p>You are linking to an existing object.</p>
		<p>The linked object will remain in its original location and also appear here.</p>
		<p>Please choose from the list of active objects:</p>
	</div>
</div>
<div id="form-container">
	<form 
		enctype="multipart/form-data" 
		action="<? echo $admin_host; ?>link.php<? echo $r->url_data(); ?>" 
		method="post" 
	>
		<div class="form">
			<div>
				<select name='wires_toid'><?php
					$items = $ob->unlinked_list($r->o);
					for($i = 0; $i < count($items); $i++)
					{
					?><option value="<? echo $items[$i]['id']; ?>"><?php 
						echo $items[$i]['name1']; 
					?></option><?php	
					}
				?></select>
			</div>
			<div>
				<input name='action' type='hidden' value='link'>
				<input 
					name='cancel' 
					type='button' 
					value='Cancel' 
					onClick="javascript:location.href='<? echo "browse.php".$r->url_data();?>';"
				>
				<input name='submit' type='submit' value='Link to Object'>
			</div>
		</div>
	</form>
</div><?php 
} 
else 
{
	/* wires */
	$wires_toid = addslashes($r->wires_toid);
	$ww->create_wire($r->o, $wires_toid);
	?><div class="self-container">
		<div class="self">
			Object linked successfully
		</div>
		<div class="self">
			<a href="<? echo $admin_host; ?>browse.php<? echo $r->url_data() ?>">continue...</a>
		</div>
	</div><?php 
}
?></div>
</div>
<?php require_once("inc/foot.php"); ?>
