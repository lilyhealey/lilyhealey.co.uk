<?php require_once("inc/head.php"); ?>
<div id="body-container">
<div id="body">
	<div class="parent-container">
<?php
	for($i = 0; $i < count($refs); $i++)
		echo $refs[$i];
?>
	</div>
<?php
if ($action != "update" && $object)
{
	// object contents
	$sql = "SELECT * 
			FROM objects 
			WHERE id = '". $objects[$o] ."' 
			AND active = 1 
			LIMIT 1";
	$result = MYSQL_QUERY($sql);
	$myrow = MYSQL_FETCH_ARRAY($result);

	//  show existing images
	$objectID = $myrow["id"];
	$i = 1;
	$sql = "SELECT * 
			FROM media 
			WHERE object = '". $objectID ."' 
			AND active = '1' 
			ORDER BY 
				rank, 
				modified, 
				created, 
				id";
	$result = MYSQL_QUERY($sql);
	$num_rows = MYSQL_NUM_ROWS($result);
	$html = "";
	while ($myrow = MYSQL_FETCH_ARRAY($result)) 
	{
		$j = $i - 1;  // There's good reason for this, I swear
		$mediaNum = "". STR_PAD($myrow["id"], 5, "0", STR_PAD_LEFT);
		$mediaFile = $dbMedia . $mediaNum .".". $myrow["type"];	
		$mediaFileDisplay = ($myrow["type"] == "pdf") ? "media/pdf.gif" : $mediaFile;
	
		$html .= "<div>";
		$html .= "<div>Image ". STR_PAD($i, 2, "0", STR_PAD_LEFT) ."</div>";
		$html .= "<div>";
		$html .= "<a href='$mediaFile' target='_blank'>";
		$html .= "<img src='". $mediaFileDisplay ."' width='160' border='0'>";
		$html .= "</a>";
		$html .= "<input type='hidden' name='mediaId[". $j ."]' value='". $myrow["id"] ."'>";
		$html .= "<input type='hidden' name='mediaType[". $j ."]' value='". $myrow["type"] ."'>";
		$html .= "<textarea name='mediaCaption[". $j ."]' cols='40' rows='3'>";
		$html .= $myrow["caption"];
		$html .= "</textarea>";
		$html .= "<select name='mediaRank[". $j ."]'>";
		for ($k = 1; $k <= $num_rows; $k++)
		{
			if ($k == $myrow["rank"])
				$html .= "<option selected value=".$k.">".$k."</option>";
			else
				$html .= "<option value=".$k.">".$k."</option>";
		}
		$html .= "</select>";
		$html .= "Rank";
		$html .= "<input name='mediaDelete[". $j ."]' type='checkbox'>Delete Image<br>";
		$i++;
	}
	//  Upload New Images
	for ($j = 0; $j < 5; $j++) 
	{
		$html .= "<div>";
		$html .= "<div>Image ". STR_PAD($i++, 2, "0", STR_PAD_LEFT) ."</div>";
		$html .= "<div>";
		$html .= "<input type='file' name='upload". $j ."'>";
		$html .= "</div>";
		$html .= "</div>";
	}

	// object contents
	$sql = "SELECT * 
			FROM objects 
			WHERE id = '". $objects[$o] ."' 
			AND active = 1 
			LIMIT 1";
	$result = MYSQL_QUERY($sql);
	$myrow = MYSQL_FETCH_ARRAY($result);
	
	if($objects[$o])
		$item = $ob->get($objects[$o]);
	else
		$item = $ob->get(0);
	$name = strip_tags($item["name1"]);
	?>
	
	<div class="self-container">
		<div class="self">
			<a href="browse.php<?php echo urlData(); ?>"><?php echo $name ?></a>
		</div>
	</div>
	<div id="form-container">
		<form
			method="post"
			enctype="multipart/form-data" 
			action="<?php echo htmlspecialchars($dbAdmin.'edit.php'.urlData()); ?>" 
		>
			<div class="form">
				<div>
					<div>Name</div>
					<div><textarea name='name1'><?php 
						echo $myrow["name1"]; 
					?></textarea></div>
				</div>
				<div>
					<div>Synopsis</div>
					<div><textarea name='deck'><?php 
						echo $myrow["deck"]; 
					?></textarea></div>
				</div>
				<div>
					<div>Detail</div>
					<div><textarea name='body'><?php 
						echo $myrow["body"]; 
					?></textarea></div>
				</div>
				<div>
					<div>Notes</div>
					<div><textarea name='notes'><?php 
						echo $myrow["notes"]; 
					?></textarea></div>
				</div>
				<div>
					<div>Begin</div>
					<div><textarea name='begin'><?php 
						echo $myrow["begin"]; 
					?></textarea></div>
				</div>
				<div>
					<div>End</div>
					<div><textarea name='end'><?php 
						echo $myrow["end"];
					?></textarea></div>
				</div>
				<div>
					<div>URL</div>
					<div><textarea name='url'><?php 
						echo $myrow["url"]; 
					?></textarea></div>
				</div>
				<div>
					<div>Rank</div>
					<div><textarea name='rank'><?php 
						echo $myrow["rank"]; 
					?></textarea></div>
				</div><?php
					// images
					echo $html;
					$html = "";
				?></div>
			<div>
				<input 
					name='submit'
					type='submit' 
					value='Update Object'
				>
				<input 
					name='cancel' 
					type='button' 
					value='Cancel' 
					onClick="javascript:location.href='<?php echo "browse.php". urlBack(); ?>';" 
				>
			</div>
			<div>
				<input 
					name='action' 
					type='hidden' 
					value='update'
				>
				<input
					name='uploadsMax'
					type='hidden'
					value='<?php echo $j;?>'
				>
				<!--input 
					name='id' 
					type='hidden' 
					value='<?php echo $id; ?>'
				-->
			</div>
		</form>
	</div>
<?php
// 	}
} 
else 
{
	// update object
	$sql = "SELECT * 
			FROM objects 
			WHERE id = '$object' 
			LIMIT 1";
	$result = MYSQL_QUERY($sql);
	$myrow = MYSQL_FETCH_ARRAY($result);

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
	$z = NULL;
	
	//  Check for differences
	if ($myrow["name1"] != $name1)
		$z .= "name1='$name1', ";
	if ($myrow["name2"] != $name2)
		$z .= "name2='$name2', ";
	if ($myrow["deck"] != $deck)
		$z .= "deck='$deck', ";
	if ($myrow["body"] != $body)
		$z .= "body='$body', ";
	if ($myrow["notes"] != $notes)
		$z .= "notes='$notes', ";
	if ($myrow["url"] != $url)
		$z .= "url='$url', ";
	if ($myrow["begin"] != $begin)
		$z .= ($begin) ? "begin = '$begin', " : "begin = null, ";
	if ($myrow["end"] != $end)
		$z .= ($end) ? "end = '$end', " : "end = null, ";
	if ($myrow["rank"] != $rank)
		$z .= ($rank) ? "rank = '$rank', " : "rank = null, ";

	//  Update edited fields only
	if ($z)
	{
		$sql = "UPDATE objects 
				SET ". $z ."modified='". date("Y-m-d H:i:s") ."' 
				WHERE id = '$object'";
		$result = MYSQL_QUERY($sql);
	}

	// delete media
	$m = FALSE;
	for ($i = 0; $i < sizeof($mediaType); $i++)
	{
		/* 
			Use sizeof(mediaType) because if checkbox is unchecked 
			that variable "doesn't exist" although the expected behavior is 
			for it to exist but be null.
		*/
		if ($mediaDelete[$i]) 
		{
			$mediaIdThis = $mediaId[$i];
			$ext = $mediaType[$i];
			$killPath = $dbMedia;
			$killFile = STR_PAD($mediaIdThis, 5, "0", STR_PAD_LEFT) .".". $ext;
			$sql = "UPDATE media 
					SET 
						active = '0', 
						modified = '". date("Y-m-d H:i:s") ."' 
					WHERE id = '$mediaIdThis'";
			$result = MYSQL_QUERY($sql);
			$m = TRUE;
		}
	}

	// upload media
	for ($i = 0; $i < $uploadsMax; $i++) 
	{
		if ($imageName = $_FILES["upload".$i]["name"])
		{
			$sql = "SELECT id 
					FROM media 
					ORDER BY id 
					DESC LIMIT 1";
			$result = MYSQL_QUERY($sql);
			$myrow = MYSQL_FETCH_ARRAY($result);

			$nameTemp = $_FILES["upload". $i]['name'];
			$typeTemp = explode(".", $nameTemp);
			$type = $typeTemp[sizeof($typeTemp) - 1];

			$targetPath = "../media/"; //$dbMedia;
			$targetFile = str_pad(($myrow["id"]+1), 5, "0", STR_PAD_LEFT) .".". $type;
			$target = $targetPath . $targetFile;
			
			// ** Image Resizing **
			// Only if folder ../media/_HI exists
			// Assume uploads are at 300dpi
			// Scale image down from 300 to 72 (24%)
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
					$targetPath = "../media/"; //$dbMedia;
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
							'". $mediaCaption[sizeof($mediaId) + $i] ."', 
							'$object', 
							'$dt', 
							'$dt')";				
				$result = MYSQL_QUERY($sql);
			}
			$m = TRUE;
		}
	}

	// update caption, weight, rank  
	$mediaCaptionLimit = sizeof($mediaCaption);
	if (sizeof($mediaId) < $mediaCaptionLimit)
		$mediaCaptionLimit = sizeof($mediaId);
	for ($i = 0; $i < $mediaCaptionLimit; $i++) 
	{
		$mediaIdThis = $mediaId[$i];
		$sql = "SELECT * 
				FROM media 
				WHERE id = '$mediaIdThis' 
				LIMIT 1";
		$result = MYSQL_QUERY($sql);
		$myrow = MYSQL_FETCH_ARRAY($result);

		if (!get_magic_quotes_gpc()) 
		{
			$mediaCaption[$i] = addslashes($mediaCaption[$i]);
			$mediaRank[$i] = addslashes($mediaRank[$i]);
		}

		$z2 = NULL;
		if ($myrow["caption"] != $mediaCaption[$i])
			$z2 .= "caption='". $mediaCaption[$i] ."', ";
		if ($myrow["rank"] != $mediaRank[$i])
			$z2 .= "rank='". $mediaRank[$i] ."', ";
		if ($z2) 
		{			
			$sql = "UPDATE media 
					SET ". $z2 ."modified = '". date("Y-m-d H:i:s") ."' 
					WHERE id = '$mediaIdThis'";
			$result = MYSQL_QUERY($sql);
			$m = TRUE;
		}
	}
	?><div class="self-container">
		<div class="self"><?php
		// Job well done?
		if ($z || $m || $z2)
			echo "Record successfully updated."; 
		else 
			echo "Nothing was edited, therefore update not required.";
		?></div>
		<div class="self">
			<a href="<?php echo $admin_path;?>edit.php<?php echo urlData(); ?>">refresh object</a>
		</div>
	</div><?php 
} ?></div>
</div>
<?php require_once("inc/foot.php"); ?>
