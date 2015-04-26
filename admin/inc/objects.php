<?php

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
		global $admin_path;
		$parents[] = ""; // is this necessary?

		$u = $this->ids_to_urls($objects);

		for ($i = 0; $i < count($objects) - 1; $i++) 
		{
			$item = $this->get($objects[$i]);
			$name = strip_tags($item["name1"]);

			// Each panel expands on title click
			$parents[$i]["url"] = $admin_path."browse/";
			for ($j = 0; $j < $i + 1; $j++)
			{
				$parents[$i]["url"] .= $u[$j];
				if ($j < $i)
					$parents[$i]["url"] .= "/";
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
		$fields = array("objects.name1", "objects.url AS o");
		$tables = array("objects", "wires");
		$where 	= array("wires.fromid = '".$o."'",
						"wires.toid = objects.id",
						"wires.active = '1'",
						"objects.active = '1'");
		$order 	= array("objects.rank", "name1");
		
		return $this->get_all($fields, $tables, $where, $order);	
	}
	
	public function urls_to_ids($u)
	{
		$fromid = 0;
		$objects = array();
		for($i = 0; $i < count($u); $i++)
		{
			$fields = array("objects.id");
			$tables = array("objects", "wires");
			$where 	= array("wires.fromid = '".$fromid."'",
							"wires.toid = objects.id",
							"objects.url = '".$u[$i]."'",
							"wires.active = '1'",
							"objects.active = '1'");
			$order 	= array("objects.name1");

			$tmp = $this->get_all($fields, $tables, $where, $order);
			$fromid = $tmp[0]['id'];
			$objects[] = $fromid;
		}
		return $objects;
	}
	
	public function ids_to_urls($objects)
	{
		$u = array();
		for($i = 0; $i < count($objects); $i++)
		{
			$o = $this->get($objects[$i]);
			$u[] = $o['url'];
		}
		return $u;
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
	
	public function url_data($o)
	{
		
	}
}
?>