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
		
		if($db->query($sql) === TRUE)
			return "Record deleted sucessfully.";
		else
			return "error: " . $db->error;
	}
	
	public function active($id)
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

?>