<?php

class Model
{
	public static function get($id)
	{
		global $db;
		if(!is_numeric($id))
			throw new Exception('Id not numeric.');
		$sql = "SELECT * 
				FROM " . $db->real_escape_string(static::table_name) . " 
				WHERE id = $id
				LIMIT 1";
		$res = $db->query($sql);
		if(!$res)
			throw new Exception("I can't read German:" . $db->error);
		if($res->num_rows == 0)
			return NULL;
		$item = $res->fetch_assoc();
		$res->close();
		return $item;
	}
	
	public static function get_all(	$fields = array("*"), 
									$tables = array("objects"), 
									$where = array(), 
									$order = array(),
									$limit = '')
	{
		global $db;
		$sql = "SELECT " . implode(", ", $fields) . " ";
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
}

class Objects extends Model
{
	const table_name = "objects";
}

class Wires extends Model
{
	const table_name = "wires";
}

class Media extends Model
{
	const table_name = "media";
}
?>