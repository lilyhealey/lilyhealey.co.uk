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
			$vars = array("name1", "deck", "body", "notes", "begin", "end", "url", "rank");
			if($r->action != "add") 
			{
		?><div class="self-container">
			<div class="self">You are adding a new object.</div>
		</div>
		<div id="form-container">
			<form 
				enctype="multipart/form-data" 
				action="<?php echo $admin_path ."add/". $u->urls(); ?>" 
				method="post"
			>
				<div id="form"><?php
				// object data
				foreach($vars as $var)
				{
					?><div>
						<div><? echo $var; ?></div>
						<div><textarea name='<? echo $var; ?>'></textarea></div>
					</div><?php
				}
				//  upload new images
				for ($j = 0; $j < $max_uploads; $j++)
				{
					?><div>
						<div>image <?php echo $j+1; ?></div>
						<div>
							<input type='file' name='upload<?php echo $j; ?>'>
						</div>
					</div><?php
				}
				?></div>
				<div>
					<input 
						name='submit' 
						type='submit' 
						value='Add Object'
					>
					<input 
						name='cancel' 
						type='button' 
						value='Cancel' 
						onClick="javascript:location.href='<? echo $admin_path."browse/".$u->urls();?>';"
					> 
				</div>
				<div>
					<input name='action' type='hidden' value='add'>
				</div>
			</form>
		</div><?php
			} 
			else
			{	
				/* objects */
				$vars = array("name1", "deck", "body", "notes", "begin", "end", "url", "rank");
				foreach($vars as $var)
					$$var = addslashes($r->$var);
	
				//  Process variables
				if (!$name1) 
					$name1 = "Untitled";
				$begin = ($begin) ? date("Y-m-d H:i:s", strToTime($begin)) : NULL;
				$end = ($end) ? date("Y-m-d H:i:s", strToTime($end)) : NULL;
				if(!$url)
					$url = slug($name1);
		
		
				$dt = date("Y-m-d H:i:s");
				$arr["created"] = "'".$dt."'";
				$arr["modified"] = "'".$dt."'";
		
				foreach($vars as $var)
					if($$var)
						$arr[$var] = "'".$$var."'";

				$toid = $ob->insert($arr);
		
				unset($arr);
		
				/* wires */
				$fromid = $u->id;
				$arr["created"] = "'".$dt."'";
				$arr["modified"] = "'".$dt."'";
				$arr["fromid"] = "'".$fromid."'";
				$arr["toid"] = "'".$toid."'";
		
				$ww->insert($arr);

				/* media */
				for ($i = 0; $i < $max_uploads; $i++) 
				{
					if ($imageName = $_FILES["upload".$i]["name"]) 
					{
						$m_rows = $mm->num_rows();

						$nameTemp = $_FILES["upload". $i]['name'];
						$typeTemp = explode(".", $nameTemp);
						$type = $typeTemp[sizeof($typeTemp) - 1];
				
						$targetFile = str_pad(($m_rows+1), 5, "0", STR_PAD_LEFT) .".". $type;				

						// ** Image Resizing **
						// Only if folder ../media/hi exists
						// First upload the raw image to ../media/hi/ folder
						// If upload works, then resize and copy to main ../media/ folder
						// To turn on set $resize = TRUE; FALSE by default
						$resize = FALSE;
						$resizeScale = 65;
						$targetPath = ($resize) ? "../media/hi/" : "../media/";
						$target = $targetPath . $targetFile;
						$copy = copy($_FILES["upload".$i]['tmp_name'], $target);
			
						if($copy) 
						{
							if($resize)
							{
								include('lib/SimpleImage.php');
								$image = new SimpleImage();
								$image->load($target);
								$image->scale($resizeScale);
								$targetPath = "../media/";
								$target = $targetPath . $targetFile;
								$image->save($target);
					
								echo "Upload $imageName SUCCESSFUL<br />";
								echo "Copy $target SUCCESSFUL<br />";
							}			
							// Add to DB's image list
							$dt = date("Y-m-d H:i:s");

							$m_arr["type"] = "'".$type."'";
							$m_arr["object"] = "'".$toid."'";
							$m_arr["created"] = "'".$dt."'";
							$m_arr["modified"] = "'".$dt."'";
							$mm->insert($m_arr);
						}
					}
				}
		?><div class="self-container">
			<div class="self">
				<p>Object added successfully.</p>
			</div>
			<div class="self">
				<a href="<? echo $admin_path; ?>browse/<? echo $u->urls(); ?>">continue... </a>
			</div>
		</div><?php 
			} 
	?></div>
</div>