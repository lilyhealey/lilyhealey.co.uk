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
		?></div><?php
		if(strtolower($r->action) != "delete") 
		{
			//  Get ALL objects where "fromid" = this object about to be deleted
			$children = $ob->children($u->id);
	
			// determine if children have other ancestors
			// ie, will they be orphaned after this object is deleted
			foreach($children as &$child)
				$child["dependent"] = TRUE;
			$fields = array("fromid");
			$tables = array("wires");
			$where 	= array("active = '1'");
			$k = 0;
			for($i = 0; $i < count($children); $i++)
			{
				$where[] = "toid = '" . $children[$i]["o"] . "'";
				$items = $ww->get_all($fields, $tables, $where);
				for($j = 0; $j < count($items); $j++)
				{
					if($items[$j]["fromid"] != $u->id)
						$children[$i]["dependent"] = FALSE;
				}
				if($children[$i]["dependent"] == TRUE)
					$k++;
			}
		//  Display warning
		?>
		<div class="self-container">
			<div class="self">WARNING!</div>
			<div class="self">You are about to permanently delete this object.</div>
			<div class="self">If this object is linked, the original will not be deleted.</div><?
			if($k) 
			{ 
			?><div class="self">The following <? echo $k; ?> objects will also be deleted as a result: </div><?	
			}
		?></div><?php

			$l = 0;
			if ($k) 
			{
				$padout = floor(log10($k)) + 1;
				if ($padout < 2) 
					$padout = 2;
		?><div></div>
		<div class="children-container"><?		
				for ($i = 0; $i < count($children); $i++) 
				{
					$j++;
					if ($children[$i]["dependent"] == TRUE)
					{
						$n = STR_PAD($j, $padout, "0", STR_PAD_LEFT);
						$url = $admin_path . "browse/" . $u->urls() . "," . $children[$i]["o"];
						$child_name = strip_tags($children[$i]["name1"]);
						?><div class="child">
							<span><? echo $n; ?></span>
							<a href="<?php echo $url; ?>"><?php echo $child_name; ?></a>
						</div><?php
					}
				}
		?></div><?php
			}
		?><div id="form-container">
			<form action="<? echo $admin_path.'delete/'.$u->urls(); ?>" method="post">
				<div class="form">
					<input name='action' type='hidden' value='delete'>
					<input 
						name='cancel' 
						type='button' 
						value='Cancel' 
						onClick="javascript:history.back();"
					> 
					<input 
						name='submit' 
						type='submit' 
						value='Delete Object'
					>
				</div>
			</form>
		</div><?php
		} 
		else 
		{
			//  Get wire that goes to this object to be deleted
			if (sizeof($u->ids) < 2) 	
				$fromid = 0;
			else
				$fromid = $u->ids[sizeof($u->ids) - 2];

			$message = $ww->delete_wire($fromid, $u->id);
	
			// if object has no wires to it, delete object
			$sql = "UPDATE objects
					SET active = '0'
					WHERE id = $u->id'";
	
		?><div class="self-container">
			<div class="self"><?php 
				echo $message; 
			?></div>
			<div class="self">
				<a href="<?php echo $admin_host; ?>browse/<? echo $u->back(); ?>">continue...</a>
			</div>
		</div><?php
		}
	?></div>
</div>