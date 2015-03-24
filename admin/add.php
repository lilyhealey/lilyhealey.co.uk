<?php require_once("inc/head.php"); ?>

<div id="body"><?php
	if ($action != "add") 
	{
	?><div class="parent-container"><?php
	for($i = 0; $i < count($refs); $i++)
		echo $refs[$i];
	?><div class="parent">You are adding a new object.</div>
	</div>
	
	<div id="form-container">
		<form 
			enctype="multipart/form-data" 
			action="<?php echo $dbAdmin ."add.php". urlData(); ?>" 
			method="post"
		>
			<div id="form">
				<div>
					<div width="90">Name</div>
					<div><textarea name='name1'></textarea></div>
				</div>
				<div>
					<div>Synopsis</div>
					<div><textarea name='deck'></textarea></div>
				</div>
				<div>
					<div>Detail</div>
					<div><textarea name='body'></textarea></div>
				</div>
				<div>
					<div>Notes</div>
					<div><textarea name='notes'></textarea></div>
				</div>
				<div>
					<div>Begin</div>
					<div><textarea name='begin'></textarea></div>
				</div>
				<div>
					<div>End</div>
					<div><textarea name='end'></textarea></div>
				</div>
				<div>
					<div>URL</div>
					<div><textarea name='url'></textarea></div>
				</div>
				<div>
					<div>Rank</div>
					<div><textarea name='rank'></textarea></div>
				</div><?php
				//  upload new images
				for ($j = 0; $j < 5; $j++)
				{
				?><div>
					<div>Image <?php echo $j+1; ?></div>
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
					onClick="javascript:location.href='<?php echo "browse.php". urlData();?>';"
				> 
			</div>
			<div>
				<input name='uploadsMax' type='hidden' value='<?php echo $j; ?>'>
				<input name='action' type='hidden' value='add'>
			</div>
		</form>
	</div><?php
	} 
	else
	{	
		/* OBJECT */
		if (!get_magic_quotes_gpc()) 
		{
			$name1 = addslashes($name1);
			$name2 = addslashes($name2);
			$deck = addslashes($deck);
			$body = addslashes($body);
			$notes = addslashes($notes); 
			$url = addslashes($url); 
			$begin = addslashes($begin);
			$end = addslashes($end);
			$rank = addslashes($rank);
		}

		//  Process variables
		if (!$name1) 
			$name1 = "Untitled";
		$begin = ($begin) ? date("Y-m-d H:i:s", strToTime($begin)) : NULL;
		$end = ($end) ? date("Y-m-d H:i:s", strToTime($end)) : NULL;

		//  Add object to database
		$dt = date("Y-m-d H:i:s");
		$sql = "INSERT INTO objects (	
					created, 
					modified, 
					name1,
					url, 
					notes, 
					deck, 
					body, 
					begin, 
					end, 
					rank) 
				VALUES(
					'$dt',
					'$dt',
					'$name1',
					'$url',
					'$notes',
					'$deck',
					'$body', ";
		$sql .= ($begin)  ? "'$begin', " : "null, ";
		$sql .= ($end)  ? "'$end', " : "null, ";
		$sql .= ($rank)  ? "'$rank')" : "null)";
		$result = MYSQL_QUERY($sql);
		$insertId = MYSQL_INSERT_ID();

		/* WIRES */
		$sql = "INSERT INTO wires (
					created, 
					modified, 
					fromid, 
					toid) 
				VALUES(
					'". date("Y-m-d H:i:s") ."', 
					'". date("Y-m-d H:i:s") ."', 
					'$object', 
					'$insertId')";
		$result = MYSQL_QUERY($sql);

		/* media */
		for ($i = 0; $i < $uploadsMax; $i++) 
		{
			if ($imageName = $_FILES["upload".$i]["name"]) 
			{
				$sql = "SELECT id 
						FROM media 
						ORDER BY id DESC 
						LIMIT 1";
				$result = MYSQL_QUERY($sql);
				$myrow = MYSQL_FETCH_ARRAY($result);

				$nameTemp = $_FILES["upload". $i]['name'];
				$typeTemp = explode(".", $nameTemp);
				$type = $typeTemp[sizeof($typeTemp) - 1];
				$targetFile = str_pad(($myrow["id"]+1), 5, "0", STR_PAD_LEFT) .".". $type;				


				// ** Image Resizing **
				// Only if folder ../media/_HI exists
				// First upload the raw image to ../media/_HI/ folder
				// If upload works, then resize and copy to main ../media/ folder
				// To turn on set $resize = TRUE; FALSE by default
				$resize = FALSE;
				$resizeScale = 65;
				$targetPath = ($resize) ? "../media/_HI/" : "../media/";
				$target = $targetPath . $targetFile;
				$copy = copy($_FILES["upload".$i]['tmp_name'], $target);
			
				if ($copy) 
				{
					if ($resize)
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
					$sql = "INSERT INTO media (
								type, 
								caption, 
								object, 
								created, 
								modified) 
							VALUES (
								'$type', 
								'', 
								'$insertId', 
								'$dt', 
								'$dt')";
					$result = MYSQL_QUERY($sql);
				}
			}
		}
	?><div class="object-container">
		<p>Object added successfully.</p>
		<a href="<?php echo $dbAdmin; ?>browse.php<?php echo urlData(); ?>">CONTINUE. . . </a>
	</div>
	<?php } ?>
</div>
<?php require_once("inc/foot.php"); ?>
