<?
class Model
{
	// takes: $id of database entry
	// returns: associative array of database entry or NULL
	public static function get($id)
	{
		global $db;
		if(!is_numeric($id))
			throw new Exception('Id not numeric.');
		$sql = "SELECT * 
				FROM " . static::table_name . " 
				WHERE id = $id
				LIMIT 1";
		$res = $db->query($sql);
		if(!$res)
			throw new Exception("I can't read German: " . $db->error);
		if($res->num_rows == 0)
			return NULL;
		$item = $res->fetch_assoc();
		$res->close();
		return $item;
	}
	
	// takes: arrays $fields, $tables, $where, $order, int $limit
	// returns: associative array of associative arrays of matching rows
	public static function get_all(	$fields = array("*"), 
									$tables = array("objects"), 
									$where = array(), 
									$order = array(),
									$limit = '',
									$distinct = TRUE)
	{
		global $db;
		$sql = "SELECT ";
		if($distinct)
			$sql .= "DISTINCT ";
		$sql .= implode(", ", $fields) . " ";
		$sql .= "FROM " . implode(", ", $tables) . " ";
		if (!empty($where))
			$sql .= "WHERE " . implode(" AND ", $where) . " ";
		if (!empty($order))
			$sql .= "ORDER BY " . implode(", ", $order) . " ";
		if (!empty($limit))
			$sql .= "LIMIT " . $limit;
		
		$res = $db->query($sql);
		if(!$res)
			throw new Exception($db->error);
		$items = array();
		while ($obj = $res->fetch_assoc())
			$items[] = $obj;
		$res->close();
		return $items;
	}
	
	public static function insert($arr)
	{
		global $db; 
		
		$keys = implode(", ", array_keys($arr));
		$values = implode(", ", array_values($arr));
		$sql = "INSERT INTO " . static::table_name . " (";
		$sql .= $keys . ") VALUES(" . $values . ")";
		
		$db->query($sql); 
		return $db->insert_id;
	}
	
	public static function update($id, $arr)
	{
		global $db;
		foreach($arr as $key => $value)
			$pairs[] = $key."=".$value;
		$z = implode(", ", $pairs);
		$sql = "UPDATE ".static::table_name." 
				SET ".$z."
				WHERE id = '".$id."'";
		
		$db->query($sql);
		
		return $sql;
	}
	
	// set active to 0
	public function deactivate($id)
	{
		global $db;
		
		if(!is_numeric($id))
			throw new Exception('Id not numeric.');
	
		$sql = "UPDATE ".static::table_name." 
				SET active = '0',
					modified = '".date("Y-m-d H:i:s")."'
				WHERE id = '$id'";
		
		echo $sql;
		if($db->query($sql) === TRUE)
			return "Record deleted sucessfully.";
		else
			return "error: " . $db->error;
	}
	
	public function exists($id)
	{
		$item = $this->get($id);
		return $item["active"] == 1;
	}
	
	public function num_rows()
	{
		global $db;
		$sql = "SELECT COUNT(id) from ".static::table_name;
		$res = $db->query($sql);
		$item = $res->fetch_assoc();
		$res->close();
		return $item["COUNT(id)"];
	}
}

// int: id, active, rank
// datetime: created, modified, begin, end, date
// tinytext: name1, name2, city, state, zip, country, phone, fax, url, email, head
// text: address1, address2
// blob: deck, body, notes
class Objects extends Model
{
	const table_name = "objects";
	
	// parents
	// children
	// ancestors
	// descendants
	
	// return the name of this object
	public function name($o)
	{
		$item = $this->get($o);
		return $item["name"];
	}
	
	// return the parents of this object
	public function parents($objects)
	{
		$parents[] = ""; // is this necessary?
		// $objects = $this->objects;
		// $ob = new Objects();

		for ($i = 0; $i < count($objects) - 1; $i++) 
		{
			$item = $this->get($objects[$i]);
			$name = strip_tags($item["name1"]);

			// Each panel expands on title click
			$parents[$i]["url"] = $admin_path . "browse.php?object=";
			for ($j = 0; $j < $i + 1; $j++)
			{
				$parents[$i]["url"] .= $objects[$j];
				if ($j < $i)
					$parents[$i]["url"] .= ",";
			}
			$parents[$i]["name"] = $name;
		}
		if($parents[0] == "")
			unset($parents);
		return $parents;
	}
	
	// return the children of this object
	public function children($o)
	{
		$fields = array("objects.name1", "objects.id AS o");
		$tables = array("objects", "wires");
		$where 	= array("wires.fromid = '".$o."'",
						"wires.toid = objects.id",
						"wires.active = '1'",
						"objects.active = '1'");
		$order 	= array("objects.rank", "name1");
		
		return $this->get_all($fields, $tables, $where, $order);	
	}
	
	public function ancestors($o)
	{
		
	}
	
	// return all descedants of this object
	// children, grandchildren, etc
	public function descendants($o)
	{
		
	}
	
	// return media attached to this object
	public function media($o)
	{
		$fields = array("*");
		$tables = array("media");
		$where 	= array("object = '".$o."'", 
						"active = '1'");
		$order 	= array("rank", "modified", "created", "id");
		
		return $this->get_all($fields, $tables, $where, $order);
	}
	
	public function unlinked_list($o)
	{
		$fields = array("objects.id", "objects.name1");
		$tables = array("objects", "wires");
		$where 	= array("objects.active = 1",
						"wires.active = 1",
						"wires.toid = objects.id",
						"wires.fromid != ".$o,
						"objects.id != ".$o);
		$order 	= array("objects.name1");
		$limit = '';
		
		return $this->get_all($fields, $tables, $where, $order, $limit);
	}
}

// int: id, active, fromid, toid
// float: weight
// datetime: created, modified
// blob: notes
class Wires extends Model
{
	const table_name = "wires";
	
	public function get_wire($fromid, $toid)
	{
		$fields = array("*");
		$tables = array(static::table_name);
		$where 	= array("fromid = '".$fromid."'",
						"toid = '".$toid."'",
						"active = '1'");
		$order 	= array();
		$limit 	= '1';
		
		$items = $this->get_all($fields, $tables, $where, $order, $limit);
		return $items[0];
	}
	
	public function delete_wire($fromid, $toid)
	{
		$item = $this->get_wire($fromid, $toid);
		$w = $item["id"];
		return $this->deactivate($w);
	}
	
	public function create_wire($fromid, $toid)
	{
		$dt = date("Y-m-d H:i:s");
		$arr["created"] = "'".$dt."'";
		$arr["modified"] = "'".$dt."'";
		$arr["fromid"] = $fromid;
		$arr["toid"] = $toid;
		return $this->insert($arr);
	}
}


// int: id, active, object, rank
// float: weight
// datetime: created, modified
// blob: caption
// varchar: type
class Media extends Model
{
	const table_name = "media";
}
?>